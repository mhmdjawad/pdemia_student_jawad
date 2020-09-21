<script>
    DBBuilder.tables = <?= json_encode(DAL::getTables()); ?>;
    console.log(DBBuilder.tables);
</script>

<form action="" class="db_builder_form">
    <div class="form-group row">
        <label class="col-sm-2 col-form-label">Table Name</label>
        <div class="col-sm-10"><input type="text" name="table_name" class="form-control"></div>
    </div>
    <table class="table table-db-builder">
        <thead>
            <th>Column Name</th>
            <th>Column Type</th>
            <th>ActualName</th>
            <th>ActualType</th>
            <th>Ctrl</th>
        </thead>
        <tbody></tbody>
    </table>
    <div class="form-control">
        <button class="btn btn-info" type="button" onclick="DBBuilder.addColumn(this);"> Add Column</button>
        <button class="btn btn-success" type="button" onclick="DBBuilder.submitCreate(this);" > Create Table</button>
    </div>
</form>
<div id="CT3">response</div>