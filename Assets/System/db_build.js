class DBBuilder{
    static addColumn(that){
        let html = `
        <tr>
            <td><input type="text" /></td>
            <td><select>
                <option>text</option>
                <option>yes/no</option>
                <option>html</option>
                <option>password</option>
                <option>image</option>
                <option>url</option>
            </select></td>
            <td class="btn btn-danger" onclick="$(this).parents('tr').remove();">remove</td>
        </tr>`;
        $(that).parents("form").find("tbody").append(html);
    }
    static submitCreate(that){
        let columns = [];
        let tableValues = $(that).parents("form").find("table tbody tr");
        for(let i =0; i < tableValues.length;i++){
            let tr = $(tableValues[i]);
            let name = tr.find("td:nth-child(1)").find("input").val();
            let type = tr.find("td:nth-child(2)").find("select").val();
            columns.push({
                "name" : name,
                "type" : type
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
}