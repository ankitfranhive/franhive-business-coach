<!DOCTYPE html>
<html>
<?php $this->load->view('includes/header'); ?>

<div class="mobile-menu-overlay"></div>

<div class="main-container">
    <div class="pd-ltr-20 xs-pd-20-10">

        <div class="page-header">
            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <div class="title">
                        <h4>Create Template</h4>
                    </div>
                </div>
                <div class="col-md-6 col-sm-12 text-right">
                    <a class="btn btn-warning" href="<?= base_url('admin_eforms/templates'); ?>" role="button">
                        Back To Templates
                    </a>
                </div>
            </div>
        </div>

        <div class="pd-20 card-box mb-30">
            <div class="clearfix">
                <div class="pull-left">
                    <h4 class="text-blue h4">Template Details</h4>
                </div>
            </div>

            <form method="post" action="<?= base_url('admin_eforms/template_store'); ?>">

                <!-- FORM TYPE -->
                <div class="form-group row">
                    <label class="col-form-label col-md-2">
                        Select Form Type <span style="color:red;">*</span>
                    </label>
                    <div class="col-md-10">
                        <select name="form_type_id" id="form_type_id" class="form-control" required>
                            <option value="">-- Select --</option>
                            <?php foreach($form_types as $ft): ?>
                                <option value="<?= (int)$ft->id ?>"><?= html_escape($ft->name) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <small class="text-muted">Selecting a form type will auto-load all fields.</small>
                    </div>
                </div>

                <!-- SLUG -->
                <div class="form-group row">
                    <label class="col-form-label col-md-2">
                        Slug <span style="color:red;">*</span>
                    </label>
                    <div class="col-md-10">
                        <input class="form-control" type="text" name="slug" required placeholder="e.g. participation-consent-release" />
                    </div>
                </div>

                <!-- TITLE -->
                <div class="form-group row">
                    <label class="col-form-label col-md-2">
                        Title <span style="color:red;">*</span>
                    </label>
                    <div class="col-md-10">
                        <input class="form-control" type="text" name="title" required placeholder="e.g. Participation Consent and Release" />
                    </div>
                </div>

                <!-- HEADING -->
                <div class="form-group row">
                    <label class="col-form-label col-md-2">Heading</label>
                    <div class="col-md-10">
                        <input class="form-control" type="text" name="heading" placeholder="Heading shown on form top" />
                    </div>
                </div>

                <!-- SUBHEADING -->
                <div class="form-group row">
                    <label class="col-form-label col-md-2">Subheading</label>
                    <div class="col-md-10">
                        <input class="form-control" type="text" name="subheading" placeholder="Subheading shown below heading" />
                    </div>
                </div>

                <!-- BODY HTML -->
                <div class="form-group row">
                    <label class="col-form-label col-md-2">Body HTML</label>
                    <div class="col-md-10">
                        <textarea class="form-control" name="body_html" rows="8" placeholder="Paste agreement text / consent content here"></textarea>
                        <small class="text-muted">This content will appear above the fields on the client form + PDF.</small>
                    </div>
                </div>

                <!-- AGREE CHECKBOX TEXT -->
                <div class="form-group row">
                    <label class="col-form-label col-md-2">Agree Checkbox Text</label>
                    <div class="col-md-10">
                        <input class="form-control" type="text" name="agree_checkbox_text" value="I have read and agree." />
                    </div>
                </div>

                <hr>

                <div class="clearfix">
                    <div class="pull-left">
                        <h4 class="text-blue h4">Fields (Auto-loaded)</h4>
                        <p class="text-muted mb-2">You can rename labels here (e.g. First Name → F Name).</p>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered" id="fields_tbl">
                        <thead>
                            <tr>
                                <th style="width:20%;">Field Name</th>
                                <th style="width:30%;">Default Label</th>
                                <th style="width:30%;">Override Label (Editable)</th>
                                <th style="width:10%;">Type</th>
                                <th style="width:10%;">Required</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- rows will be inserted by JS -->
                        </tbody>
                    </table>
                </div>

                <div class="form-group row">
                    <div class="col-md-6 offset-md-2">
                        <button type="submit" class="btn btn-warning">Save Template</button>
                    </div>
                </div>

            </form>
        </div>

    </div>
</div>

<script>
    const sel = document.getElementById('form_type_id');
    const tblBody = document.querySelector('#fields_tbl tbody');

    function clearRows() {
        tblBody.innerHTML = '';
    }

    sel.addEventListener('change', async function () {
        clearRows();
        const id = this.value;
        if (!id) return;

        try {
            const res = await fetch("<?= base_url('admin_eforms/ajax_form_type_fields/'); ?>" + id);
            const fields = await res.json();

            fields.forEach((f) => {
                const tr = document.createElement('tr');

                // Field name (hidden input posted)
                const td1 = document.createElement('td');
                td1.innerHTML = `
                    <input class="form-control" name="field_name[]" value="${f.name}" readonly>
                `;

                // Default label (text only)
                const td2 = document.createElement('td');
                td2.textContent = f.label;

                // Override label (editable)
                const td3 = document.createElement('td');
                td3.innerHTML = `
                    <input class="form-control" name="field_label_override[]" value="${f.label}">
                `;

                // Type
                const td4 = document.createElement('td');
                td4.textContent = f.type;

                // Required
                const td5 = document.createElement('td');
                td5.innerHTML = f.is_required ? '<span class="badge badge-success">Yes</span>' : '<span class="badge badge-secondary">No</span>';

                tr.appendChild(td1);
                tr.appendChild(td2);
                tr.appendChild(td3);
                tr.appendChild(td4);
                tr.appendChild(td5);

                tblBody.appendChild(tr);
            });

        } catch (e) {
            alert('Failed to load fields. Please check ajax_form_type_fields route/controller.');
            console.error(e);
        }
    });
</script>

</body>
<?php $this->load->view('includes/footer'); ?>
</html>
