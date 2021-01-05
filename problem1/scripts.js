$(document).ready( function() {
    let rowIndex = 1;

    // $('button.database').click( function() {
    //     let buttonName = $(this).val();
    //     $('#table').load("load_table.php", {
    //         button_name : buttonName
    //     });
    //     $('.path').html(buttonName+">");
    // });

    $('nav.nav-bar').on('click', 'a', function() {
        $('.nav-bar a').removeClass('active')
        $(this).addClass('active');
    })

    $('.main-container').on('click', '.left-container button.db-button', function() {
        $('button.db-button').removeClass('active')
        $(this).addClass('active');
    })

    $('.main-container').on("click",'.left-container button.database', function() {
        let buttonName = $(this).val();
        $('#table').load("templates/home/load_table.php", {
            button_name : buttonName
        });
        $('.path').html(buttonName+">");
    });

    $('.main-container').on("click",'.right-container button.show-table', function() {
        let tableName = $(this).val();
        if ($(this).attr('data-loaded') === "yes") {
            if ($(`table#${tableName}`).css('display') === 'none') {
                $(`table#${tableName}`).css('display','table');
            } else {
                $(`table#${tableName}`).css('display','none');
            }
        } else {
            $(this).attr('data-loaded','yes');
            $(`table#${tableName}`).load("templates/home/load_record.php", {
                table_name : tableName
            });
        }
    });

    $('.main-container').on("click",'.right-container button.modify-table', function() {
        let tableName = $(this).val();
        let error = false;
        let res = '';
        $(`#${tableName} tr`).each(function(){
            let rowArr = [];
            $(this).children('td').each(function (i,e) {
                const field = {
                    'key' : $(this).children('span').attr('value'),
                    'val' : $(this).children('span').text()
                }
                rowArr.push(field)
                //console.log(e)
            })
            const url = 'templates/home/update_table.php';
            const fVal = {
                'key' : $(this).children('td').children('span.0').attr('value'),
                'val' : $(this).children('td').children('span.0').attr('data-value')
            }
            const data = {
                table_data : rowArr,
                table_name : tableName,
                f_val : fVal
            }
            // console.log(fVal)
            // console.log(rowArr)

            if(rowArr.length > 0) {
                $.post(url, data, function (response) {
                    if (response !== 'error') {
                    } else {
                        alert(`SQL statement error`);
                    }
                });
            }
        });
    });


    // $(".delete-button").click(function () {
    //     if(confirm(`are you sure you want to delete database "${$(this).val()}"?`)){
    //         let databaseName = $(this).val();
    //         let url = 'delete_database.php',
    //         data =  {'database': databaseName};
    //         $.post(url, data, function (response) {
    //             // Response div goes here.
    //             $(".left-container").load(" .left-container");
    //             alert(`${response} is deleted successfully`);
                
    //         });
    //     }
    // });

    $(".main-container").on("click", ".left-container .delete-button" ,function(){
        if(confirm(`are you sure you want to delete database "${$(this).val()}"?`)){
            let databaseName = $(this).val();
            let url = 'templates/home/delete_database.php';
            let data =  {'database': databaseName};
            $.post(url, data, function (response) {
                // Response div goes here.
                $(".left-container").load(" .left-container > *");
                alert(`${response} is deleted successfully`);
                
            });
        }
      });


      $(".main-container").on("click", ".right-container .delete-table" ,function(){
        if(confirm(`are you sure you want to delete table "${$(this).val()}"?`)){
            let tableName = $(this).val();
            let url = 'templates/home/delete_table.php';
            let data =  {'table': tableName};
            $.post(url, data, function (response) {
                // Response div goes here.
                $("#table").load("templates/home/load_table.php");
                alert(`${response} is deleted successfully`);
                
            });
        }
      });

    // $(".add-database").click(function () {
    //     if(confirm(`are you sure you want to add database "${$('#database-name').val()}"?`)){
    //         let databaseName = $('#database-name').val();
    //         let url = 'add_database.php',
    //         data =  {'dbName': databaseName};
    //         $.post(url, data, function (response) {
    //             if (response !== 'error') {
    //                 $(".left-container").load(" .left-container > *");
    //                 alert(`${response} is added successfully`);
    //             } else {
    //                 alert(`SQL statement error`);
    //             }
                
    //         });
    //     }
    // });

    $('.top-container').on("click", ".add-database",function(){
        if(confirm(`are you sure you want to add database "${$('#database-name').val()}"?`)){
            let databaseName = $('#database-name').val();
            let url = 'add_database.php';
            let data =  {'dbName': databaseName};
            $.post(url, data, function (response) {
                if (response !== 'error') {
                    $(".left-container").load(" .left-container > *");
                    alert(`${response} is added successfully`);
                } else {
                    alert(`SQL statement error`);
                }
                
            });
        }
      });

      // this is the id of the form
    // $("#upload-form").submit(function(e) {

    //     e.preventDefault(); // avoid to execute the actual submit of the form.

    //     //const form = $(this);
        
    //     const form = new FormData(this);
    //     //const url = "upload_table.php";
    //     const url = "quick_upload_table.php";

    //     $.ajax({
    //         type: "POST",
    //         url: url,
    //         data: form, 
    //         success: function(data)
    //         {
    //             $(".table-upload").html(data);
    //             //alert(data); // show response from the php script.
    //         },
    //         cache: false,
    //         contentType: false,
    //         processData: false
    //         });
    // });

    $(document).on('click',".upload-button",function(e) {
        console.log("hello")
        const url = "templates/compare/quick_upload_table.php";
        const url2 = "templates/compare/compare_upload_table.php";
        const form = $('#save-upload-form')[0];
        console.log(form);
        const formData = new FormData(form);

        $.ajax({
            type: "POST",
            url: url,
            data: formData, 
            success: function(data)
            {
                $(".set-table-field").html(data);
                //alert(data); // show response from the php script.
            },
            error: function(data){
                alert(data);
            },
            cache: false,
            contentType: false,
            processData: false
            });

        $.ajax({
            type: "POST",
            url: url2,
            data: formData, 
            success: function(data)
            {
                $("#table").html(data);
                //alert(data); // show response from the php script.
            },
            error: function(data){
                alert(data);
            },
            cache: false,
            contentType: false,
            processData: false
        });
    });



    $(document).on('click',".addrow", function () {
        let row = `<tr>
        <td><input type="text" name="fields[${rowIndex}][field_name]"></td>
        <td><select name="fields[${rowIndex}][type]">
            <option value="int">INT</option>
            <option value="varchar">VARCHAR</option>
            <option value="date">CHAR</option>
            </select>
        </td>
        <td><input type="number" name="fields[${rowIndex}][length]"></td>
        <td><input type="checkbox" name="fields[${rowIndex}][pk]" value="primary key"></td>
        <td><input type="checkbox" name="fields[${rowIndex}][null]" value="null"></td>
    </tr>`;
        $("#add-table").append(row);
        rowIndex++;
    });


    $(".main-container").on("click", ".select-database" ,function(){
        const buttonName = $(this).val();
        $('.right-container .compare-table').load("templates/compare/compare_table.php", {
            button_name : buttonName
        });

      });

    $(".main-container").on("click", "button.select-fact-table" ,function(){
        const buttonName = $(this).val();
        $('.right-container .table-form').load("templates/star_schema/establish_relation.php", {
            button_name : buttonName
        });

    });

    $(".main-container").on('submit','#compare-table-form',function(e) {
        e.preventDefault(); // avoid to execute the actual submit of the form.

        //const form = $(this);
        
        const form = new FormData(this);
        const url = $(this).attr('action')
        $.ajax({
            type: "POST",
            url: url,
            data: form, 
            success: function(data)
            {
                $("#table").html(data);
                //alert(data); // show response from the php script.
            },
            cache: false,
            contentType: false,
            processData: false
            });
    });

    $(".main-container").on('submit',"#make-fact-table",(function(e) {

        e.preventDefault(); // avoid to execute the actual submit of the form.
    
        const form = new FormData(this);
        const url = $(this).attr('action');
        
        console.log("gel")
        
        $.ajax({
               type: "POST",
               url: url,
               data: form, // serializes the form's elements.
               success: function(data)
               {
                    $("#table").html(data);
                //    alert(data); // show response from the php script.
               },
               cache: false,
               contentType: false,
               processData: false
             });
    
        
    }));

    $(document).on('change','.select-dim',function(){
        table = $(this).val();
        const url = "templates/star_schema/get_options.php";
        const name = $(this).attr('data-col');
        data = {
            table_name : table
        };
        $.post(url,data,function(response) {
            const emptyOption = "<option value=''>---</option>";
            $('#dim-field'+name).empty().append(emptyOption).append(response);
        });
   });

   $(document).on('click','.select-dim-tables',function () {
       const url = 'templates/show_relation/dim_tables.php';
       const buttonName = $(this).val();
       $('#table .table-left').load(url, 
        {
        button_name : buttonName
        });
    });


    //show star schema tables 
    $(".main-container").on('submit',"#select-dim-tables",(function(e) {

        e.preventDefault(); // avoid to execute the actual submit of the form.
    
        const form = new FormData(this);
        const url = $(this).attr('action');
        console.log("gel")
        
        $.ajax({
               type: "POST",
               url: url,
               data: form, // serializes the form's elements.
               success: function(data)
               {
                    $("#table .table-right").html(data);
                //    alert(data); // show response from the php script.
               },
               cache: false,
               contentType: false,
               processData: false
             });
    
        
    }));

    $(document).on('mouseenter','.highlight-dim',function(e) {
        dimCol = $(this).text();
        document.getElementById(dimCol).style.backgroundColor = 'rgba(0, 0, 0, 0.479)'; 
    
    })

    $(document).on('mouseleave','.highlight-dim',function(e) {
        dimCol = $(this).text();
        document.getElementById(dimCol).style.backgroundColor = 'white';
    
    })

    $(document).on('click','.select-export-tables',function () {
        const url = 'tables/show_export_tables.php';
        const buttonName = $(this).val();
        $('.right-container .table-form').load(url, 
         {
         button_name : buttonName
         });
     });

         //show star schema tables 
    $(".main-container").on('submit',"#select-export-tables",(function(e) {

        e.preventDefault(); // avoid to execute the actual submit of the form.
    
        const form = new FormData(this);
        const url = $(this).attr('action');
        console.log("gel")
        
        $.ajax({
               type: "POST",
               url: url,
               data: form, // serializes the form's elements.
               success: function(data)
               {
                    $("#table").html(data);
                //    alert(data); // show response from the php script.
               },
               cache: false,
               contentType: false,
               processData: false
             });
    
        
    }));


    //change field select options when table changed in select
    $(document).on('change','.export-table-select',function(){
        table = $(this).val();
        const url = "templates/export_table/get_tables.php";
        const name = $(this).attr('id');
        //get row number from id
        const id = name.slice(-1);
        data = {
            table_name : table
        };
        $.post(url,data,function(response) {
            const emptyOption = "<option value=''>---</option>";
            $('#export-field'+id).empty().append(emptyOption).append(response);
        });
   });

   $(document).on('click',".add-export-row", function () {

        const url = 'templates/export_table/add_export_table_row.php';


        let tableArr = []
        let fieldArr = []
        $('#export-table0 option').each(function(){

            if($(this).val()) {
                tableArr.push($(this).val())
            }
        });


        $('#export-field0 option').each(function(){
            if($(this).val()) {
                fieldArr.push($(this).val())
            }
        });

        const data = {
            tables: tableArr,
            fields: fieldArr
        }

        $.post(url,data,function(response) {

            $('#export-file-table').append(response);
        });
    });

    $(document).on('click',".delete-export-row", function () {

        const url = 'templates/export_table/delete_export_table_row.php';


        $.post(url,function(response) {
            // console.log(response);
            let id = response;
            if(id > 0)
            $('tr#'+id).remove();
        });
    });


    $(document).on('click',".add-import-row", function () {

        const url = 'templates/compare/add_import_row.php';
        const url2 = 'templates/compare/add_display_column.php';


        const fileCols =  $('#file-length').val();
        const data = {
            column: fileCols
        }

        $.post(url2,function(response) {
            let id = Number(response) + 1
            const row = `<td id='${id-1}'>Column 1</td>`

            $(`#import-table-layout`).append(row);
        });

        $.post(url,data,function(response) {

            $('#import-file-table').append(response);
        });


    });

    $(document).on('click',".delete-import-row", function () {

        const url = 'templates/compare/delete_import_row.php';


        $.post(url,function(response) {
            // console.log(response);
            let id = response;
            if(id > 0)
            $('tr#'+id).remove();
            $('td#'+id).remove();
        });
    });

    $(document).on('change','.import-column-num',function(){
        const name = $(this).attr('name')
        const row = name.substring(7,8)
        const val = $(this).val()
        $('#import-table-layout td#'+row).html('Column '+val)
        console.log( $('td#'+row).html())

   });

   $(document).on('click','.import-layout',function(){
        const url = 'templates/compare/import_layout.php';
        const url2 = 'templates/compare/import_compare_layout.php';

        const tableName = $('.layout-select').val();
        const tableSize = $('#file-length').val();
        if(tableName) {
            const data = {
                table_name : tableName,
                table_size : tableSize
            }
            $.post(url,data,function(response) {
                $('#import-file-table').html(response)
            });

            $.post(url2,data,function(response) {
                $('#compare-table-2').html(response)
            });
        }
    });
   

});


