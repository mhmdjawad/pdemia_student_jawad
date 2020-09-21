class DBBuilder{
    static getTables(_success){
        if(DBBuilder.tables != null){
            _success(DBBuilder.table);
        }
        else{
            let fd = new FormData();
            fd.append("fct","DBBuilder/getTables");
            SYS.xhr_post(null,fd,"text","no",function(r,o){
                DBBuilder.tables = r;
                _success(DBBuilder.table);
            });
        }
    }
    static getLookUpSelect(){
        let html = `<select onchange="DBBuilder.handleInputChange(this);" class="form-control LookupSel"
        style="display:none"
         >`;
        for(let i in DBBuilder.tables){
            html += `<option>${DBBuilder.tables[i]}</option>`;
        }
        html += `</select>`;
        return html;
    }
    static addColumn(that){
        let html = `
        <tr>
            <td><input onchange="DBBuilder.handleInputChange(this);" class="form-control" type="text" /></td>
            <td>
            <select onchange="DBBuilder.handleInputChange(this);" class="form-control TypeSel" >
                <option>text</option>
                <option>yes/no</option>
                <option>html</option>
                <option>password</option>
                <option>image</option>
                <option>url</option>
                <option>lookup</option>
            </select>
            ${DBBuilder.getLookUpSelect()}
            </td>
            <td class="actualNameTd">...</td>
            <td class="actualTypeTd">...</td>
            <td class="btn btn-danger" onclick="$(this).parents('tr').remove();">remove</td>
        </tr>`;
        $(that).parents("form").find("tbody").append(html);
    }
    static submitCreate(that){
        let columns = [];
        let tableValues = $(that).parents("form").find("table tbody tr");
        for(let i =0; i < tableValues.length;i++){
            let tr = $(tableValues[i]);
            let name = tr.find("input").val();
            let type = tr.find(".TypeSel").val();
            let actualName = tr.find(".actualNameTd").val();
            let actualType = tr.find(".actualTypeTd").val();
            columns.push({
                "name" : name,
                "type" : type,
                "actual_name" : actualName,
                "actual_type" : actualType
            });
        }
        let tablename = $(that).parents("form").find("[name='table_name']").val();
        let fd = new FormData();
        fd.append("key","DBBuilder/createTable");
        fd.append("table_name",tablename);
        fd.append("columns",JSON.stringify(columns));
        SYS.xhr_post(null,fd,"text","CT3",function(r,o){
            $(`#${o}`).html(r);
        });
    }
    static handleInputChange(that){
        let name = $(that).parents("tr").find("td:nth-child(1) input").val();
        let type = $(that).parents("tr").find(".TypeSel").val();
        DBBuilder.updateRow(that,name,type);
    }
    
    static updateRow(that,name,type){
        $(that).parents("tr").find(".LookupSel").hide();
        let actualName = name;
        let actualType = "varchar(255)";
        if(type=="text"){
            actualName = name;
            actualType = "varchar(255)";
        }
        else if(type=="yes/no"){
            actualName = `active_${name}`;
            actualType = "varchar(1) [1]";
        }
        else if(type=="html"){
            actualName = `html`;
            actualType = "TEXT";
        }
        else if(type=="password"){
            actualName = `password`;
        }
        else if(type=="url"){
            actualName = `url_${name}`;
        }
        else if(type=="image"){
            actualName = `image_${name}`;
        }
        else if(type=="lookup"){
            $(that).parents("tr").find(".LookupSel").show();
            let lookup = $(that).parents("tr").find(".LookupSel").val();
            actualName = `${lookup}_fk`;
            actualType = "int unsigned";
        }
        else{
            actualName = `not handled type ${type}`;
        }

        $(that).parents("tr").find(".actualNameTd").text(actualName);
        $(that).parents("tr").find(".actualTypeTd").text(actualType);

    }
}