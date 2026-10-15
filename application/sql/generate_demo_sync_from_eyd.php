<?php
/**
 * Regenerate sync SQL: compare eyd vs franhive (demo) on the shared MySQL host.
 * Usage: php application/sql/generate_demo_sync_from_eyd.php
 *
 * Outputs:
 *   application/sql/sync_demo_schema_from_eyd.sql
 *   application/sql/sync_demo_schema_from_eyd_safe.sql
 *   application/sql/sync_demo_schema_from_eyd_modify_optional.sql
 *   application/sql/sync_demo_schema_from_eyd_summary.json
 */

$root = dirname(__DIR__, 2);
$host = getenv('DB_HOST') ?: '134.209.145.105';
$port = (int)(getenv('DB_PORT') ?: 8972);
$user = getenv('DB_USER') ?: 'admin';
$pass = getenv('DB_PASS') ?: 'Franh1ve@dmin';
$eyd = 'eyd';
$demo = 'franhive';

function connect($host, $port, $user, $pass, $db)
{
    $m = new mysqli($host, $user, $pass, $db, $port);
    if ($m->connect_error) {
        fwrite(STDERR, "Connect failed ($db): {$m->connect_error}\n");
        exit(1);
    }
    $m->set_charset('utf8mb4');
    return $m;
}

function fetch_schema($mysqli, $schema)
{
    $schema = $mysqli->real_escape_string($schema);
    $sql = "SELECT TABLE_NAME, COLUMN_NAME, COLUMN_TYPE, IS_NULLABLE, COLUMN_DEFAULT, EXTRA, COLUMN_KEY, COLLATION_NAME
            FROM INFORMATION_SCHEMA.COLUMNS
            WHERE TABLE_SCHEMA = '$schema'
            ORDER BY TABLE_NAME, ORDINAL_POSITION";
    $res = $mysqli->query($sql);
    $out = ['tables' => [], 'columns' => []];
    while ($row = $res->fetch_assoc()) {
        $t = $row['TABLE_NAME'];
        $out['tables'][$t] = true;
        $c = $row['COLUMN_NAME'];
        $out['columns'][$t][$c] = [
            'type' => $row['COLUMN_TYPE'],
            'nullable' => $row['IS_NULLABLE'],
            'default' => $row['COLUMN_DEFAULT'],
            'extra' => $row['EXTRA'],
            'key' => $row['COLUMN_KEY'],
            'collation' => $row['COLLATION_NAME'],
        ];
    }
    return $out;
}

function qident($name)
{
    return '`' . str_replace('`', '``', $name) . '`';
}

function create_table_dependencies($createSql)
{
    if (!preg_match_all('/REFERENCES `([^`]+)`/i', $createSql, $m)) {
        return [];
    }
    return array_values(array_unique($m[1]));
}

function sort_tables_for_create($creates)
{
    $remaining = array_keys($creates);
    $sorted = [];
    $guard = count($remaining) * count($remaining) + 5;
    while ($remaining && $guard-- > 0) {
        $picked = null;
        foreach ($remaining as $table) {
            $deps = create_table_dependencies($creates[$table]);
            $blocked = false;
            foreach ($deps as $dep) {
                if (in_array($dep, $remaining, true)) {
                    $blocked = true;
                    break;
                }
            }
            if (!$blocked) {
                $picked = $table;
                break;
            }
        }
        if ($picked === null) {
            return array_merge($sorted, $remaining);
        }
        $sorted[] = $picked;
        $remaining = array_values(array_diff($remaining, [$picked]));
    }
    return $sorted;
}

function default_clause($def, $nullable, $type)
{
    if ($def === null) {
        return $nullable === 'YES' ? ' NULL' : ' NOT NULL';
    }
    if (is_string($def) && stripos($def, 'current_timestamp') !== false) {
        return ' DEFAULT CURRENT_TIMESTAMP' . ($nullable === 'YES' ? ' NULL' : ' NOT NULL');
    }
    $typeLower = strtolower($type);
    if (preg_match('/^(int|decimal|float|double|tinyint|smallint|mediumint|bigint)/', $typeLower)) {
        return ' NOT NULL DEFAULT ' . $def;
    }
    if ($def === 'NULL' && $nullable === 'YES') {
        return ' NULL DEFAULT NULL';
    }
    $escaped = str_replace("'", "''", (string)$def);
    return ($nullable === 'YES' ? ' NULL' : ' NOT NULL') . " DEFAULT '" . $escaped . "'";
}

$eydDb = connect($host, $port, $user, $pass, $eyd);
$demoDb = connect($host, $port, $user, $pass, $demo);
$eydSchema = fetch_schema($eydDb, $eyd);
$demoSchema = fetch_schema($demoDb, $demo);

$lines = [];
$lines[] = '-- Sync demo (franhive) schema toward eyd';
$lines[] = '-- Generated: ' . date('Y-m-d H:i:s');
$lines[] = 'SET NAMES utf8mb4;';
$lines[] = "SET SESSION sql_mode = '';";
$lines[] = 'USE `franhive`;';
$lines[] = '';

$missingTables = array_diff(array_keys($eydSchema['tables']), array_keys($demoSchema['tables']));
$extraTables = array_diff(array_keys($demoSchema['tables']), array_keys($eydSchema['tables']));

$createSqlByTable = [];
foreach ($missingTables as $table) {
    $r = $eydDb->query('SHOW CREATE TABLE ' . qident($table));
    if ($r && ($row = $r->fetch_assoc())) {
        $createSqlByTable[$table] = $row['Create Table'];
    }
}
foreach (sort_tables_for_create($createSqlByTable) as $table) {
    $create = preg_replace('/^CREATE TABLE `/', 'CREATE TABLE IF NOT EXISTS `', $createSqlByTable[$table]);
    $lines[] = $create . ';';
    $lines[] = '';
}

$alterAdds = [];
$alterMods = [];
$report = [
    'missing_tables' => array_values($missingTables),
    'extra_tables_demo_only' => array_values($extraTables),
    'missing_columns' => [],
    'different_columns' => [],
];

$commonTables = array_intersect(array_keys($eydSchema['tables']), array_keys($demoSchema['tables']));
sort($commonTables);

foreach ($commonTables as $table) {
    $eydCols = $eydSchema['columns'][$table] ?? [];
    $demoCols = $demoSchema['columns'][$table] ?? [];
    foreach (array_diff(array_keys($eydCols), array_keys($demoCols)) as $col) {
        $meta = $eydCols[$col];
        $report['missing_columns'][] = "$table.$col";
        $keys = array_keys($eydCols);
        $idx = array_search($col, $keys, true);
        $after = $idx > 0 ? ' AFTER ' . qident($keys[$idx - 1]) : '';
        $def = qident($col) . ' ' . $meta['type'];
        if ($meta['collation'] && preg_match('/char|text|enum|set/i', $meta['type'])) {
            $def .= ' COLLATE ' . $meta['collation'];
        }
        $def .= default_clause($meta['default'], $meta['nullable'], $meta['type']);
        if ($meta['extra']) {
            $def .= ' ' . $meta['extra'];
        }
        $alterAdds[] = 'ALTER TABLE ' . qident($table) . ' ADD COLUMN ' . $def . $after . ';';
    }
    foreach ($eydCols as $col => $emeta) {
        if (!isset($demoCols[$col])) {
            continue;
        }
        $dmeta = $demoCols[$col];
        $same = ($emeta['type'] === $dmeta['type']
            && $emeta['nullable'] === $dmeta['nullable']
            && (string)$emeta['default'] === (string)$dmeta['default']
            && $emeta['extra'] === $dmeta['extra']);
        if (!$same) {
            $report['different_columns'][] = ['table' => $table, 'column' => $col, 'eyd' => $emeta, 'demo' => $dmeta];
            $def = qident($col) . ' ' . $emeta['type'];
            if ($emeta['collation'] && preg_match('/char|text|enum|set/i', $emeta['type'])) {
                $def .= ' COLLATE ' . $emeta['collation'];
            }
            $def .= default_clause($emeta['default'], $emeta['nullable'], $emeta['type']);
            if ($emeta['extra']) {
                $def .= ' ' . $emeta['extra'];
            }
            $alterMods[] = 'ALTER TABLE ' . qident($table) . ' MODIFY COLUMN ' . $def . ';';
        }
    }
}

if ($missingTables) {
    array_splice($lines, 6, 0, ['-- Tables present on eyd but missing on demo (CREATE TABLE from eyd):', '']);
}
if ($alterAdds) {
    $lines[] = '-- Missing columns on demo (add from eyd):';
    $lines[] = implode("\n", $alterAdds);
    $lines[] = '';
}
if ($alterMods) {
    $lines[] = '-- Columns that differ in type/default (modify demo to match eyd):';
    $lines[] = '-- Review carefully before running on production demo.';
    $lines[] = implode("\n", $alterMods);
    $lines[] = '';
}
if ($extraTables) {
    $lines[] = '-- Demo-only tables (not on eyd) — not dropped automatically:';
    foreach ($extraTables as $t) {
        $lines[] = '--   ' . $t;
    }
}

$sqlDir = $root . '/application/sql';
$full = implode("\n", $lines) . "\n";
file_put_contents($sqlDir . '/sync_demo_schema_from_eyd.sql', $full);

$marker = '-- Columns that differ in type/default (modify demo to match eyd):';
$pos = strpos($full, $marker);
$safe = $pos === false ? $full : substr($full, 0, $pos);
file_put_contents($sqlDir . '/sync_demo_schema_from_eyd_safe.sql', rtrim($safe) . "\n");

$modify = "-- OPTIONAL: modify existing columns on demo to match eyd\n-- Run only after backup.\n\nSET NAMES utf8mb4;\nSET SESSION sql_mode = '';\nUSE `franhive`;\n\n";
if ($pos !== false) {
    $modify .= substr($full, $pos);
}
file_put_contents($sqlDir . '/sync_demo_schema_from_eyd_modify_optional.sql', $modify);

file_put_contents($sqlDir . '/sync_demo_schema_from_eyd_summary.json', json_encode($report, JSON_PRETTY_PRINT));

echo "Missing tables: " . count($missingTables) . "\n";
echo "Missing columns: " . count($report['missing_columns']) . "\n";
echo "Different columns: " . count($report['different_columns']) . "\n";
echo "Written to {$sqlDir}/sync_demo_schema_from_eyd_*.sql\n";
