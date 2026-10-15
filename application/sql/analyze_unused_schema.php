<?php
/**
 * Scan application code for database table name references.
 * Usage: php application/sql/analyze_unused_schema.php [eyd|franhive]
 */
$schema = $argv[1] ?? 'eyd';
$host = getenv('DB_HOST') ?: '134.209.145.105';
$port = (int)(getenv('DB_PORT') ?: 8972);
$user = getenv('DB_USER') ?: 'admin';
$pass = getenv('DB_PASS') ?: 'Franh1ve@dmin';

$root = dirname(__DIR__, 2);
$m = new mysqli($host, $user, $pass, $schema, $port);
if ($m->connect_error) {
    fwrite(STDERR, $m->connect_error . "\n");
    exit(1);
}

$r = $m->query("SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_SCHEMA='" . $m->real_escape_string($schema) . "' AND TABLE_TYPE='BASE TABLE' ORDER BY TABLE_NAME");
$tables = [];
while ($row = $r->fetch_assoc()) {
    $tables[] = $row['TABLE_NAME'];
}

function codeReferencesTable($root, $table)
{
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root . '/application', RecursiveDirectoryIterator::SKIP_DOTS));
    foreach ($iterator as $file) {
        if (!$file->isFile() || !preg_match('/\.php$/i', $file->getPathname())) {
            continue;
        }
        $path = $file->getPathname();
        if (strpos($path, '/logs/') !== false || strpos($path, '/cache/') !== false) {
            continue;
        }
        $content = file_get_contents($path);
        if (stripos($content, $table) !== false) {
            return true;
        }
    }
    return false;
}

$unused = [];
$used = [];
foreach ($tables as $t) {
    if (strpos($t, 'lic_') === 0) {
        $used[] = $t;
        continue;
    }
    if (codeReferencesTable($root, $t)) {
        $used[] = $t;
    } else {
        $cnt = 0;
        $q = $m->query('SELECT COUNT(*) c FROM `' . str_replace('`', '``', $t) . '`');
        if ($q) {
            $cnt = (int)$q->fetch_assoc()['c'];
        }
        $unused[] = ['table' => $t, 'rows' => $cnt];
    }
}

echo "Schema: $schema\n";
echo 'Used in PHP: ' . count($used) . "\n";
echo 'Unused in PHP: ' . count($unused) . "\n\n";
foreach ($unused as $u) {
    echo sprintf("%-40s %6d rows\n", $u['table'], $u['rows']);
}
