<?php
if ( isset($_POST["submit"]) ) {

    if ( isset($_FILES["file"])) {

        //if there was an error uploading the file
        if ($_FILES["file"]["error"] > 0) {
            echo "Return Code: " . $_FILES["file"]["error"] . "<br />";

        }
        else {


            //if file already exists
            if (file_exists("upload/" . $_FILES["file"]["name"])) {
                echo $_FILES["file"]["name"] . " already exists. ";
            }
            else {
                //Store file in directory "upload" with the name of "uploaded_file.txt"
                $storagename = "uploaded_file.txt";
                move_uploaded_file($_FILES["file"]["tmp_name"], "upload/" . $storagename);

            }
        }
    } else {
        echo "No file selected <br />";
    }
    if ( $f = fopen( "upload/" . $storagename , 'r' ) ) {

//        echo "File opened.<br />";

        echo "<html><head><link rel='stylesheet' href='style.css'></head><body>

               <a href='index.html'  style='position: absolute;top: 13%;right: 27%; left:60%; z-index: 3;'> <button id='btn1'>Upload new File</button></a>
              <div style='position: relative;left: 20%;top: 52%;' class='first'><table class='table' border='1'>\n\n";

        $i=0;
        $data=array();
        while (($line = fgetcsv($f)) !== false) {
            if($i==0)
                $fields=$line;
            echo "<tr class='tbtr'>";
            $data[$i]=$line;
            foreach ($line as $cell) {
                echo "<td>" . htmlspecialchars($cell) . "</td>";
            }
            echo "</tr>\n";
            $i++;
        }
        $unique=array();
        $i=0;
        foreach($fields as $field){
            $unique[$i]=array();
            $i++;
        }
        $i=0;
        foreach($data as $row){
            if($i!=0){
                $j=0;
                foreach($row as $cell){
                    if(!in_array($cell,$unique[$j])){
                        array_push($unique[$j],$cell);
                    }
                    $j++;
                }
            }
            $i++;
        }
        fclose($f);
        echo "\n</table></div>";
        echo "<div style='position: relative;top:-21%;left: 10%;'>";
        for($i=1;$i<count($fields);$i++){
            echo '<label for="col-'.$fields[$i].'">'.$fields[$i].': </label>';
            echo '<select class="table-param" id="col-'.$fields[$i].'" name="col-'.$fields[$i].': ">';
            echo '<option value="0">Select</option>';
            foreach($unique[$i] as $col){
                echo '<option value="'.$col.'">'.$col.'</option>';
            }
            echo '</select></br><br>';
        }
        echo "</div>";
        echo '<div style="position: relative;left: 54%;" class="secondOne"></div>';
        echo "</body></html>";
    }
}
?>
<?php

if ( isset($_POST["submit"]) ) {?>
    <script src="js/jquery.js"></script>
    <script>
        $(document).ready(function(){
            $('.secondOne').append($('.first').html());
            $('.table-param').on('change',function(){
                var curVal=new Array();
                $('select').each(function(){
                    if($(this).val().toString()!=0)
                        curVal.push($(this).val().toString());
                })
                $(".secondOne .table tbody>tr").hide();
                $(".secondOne .table td").filter(function(){
                    return $(this).text()==curVal[0];
                }).parent("tr").addClass(curVal[0]);
                $(".secondOne .table td").filter(function(){
                    if(curVal[1]!=0)
                        return $(this).text()==curVal[1];
                    else
                        return false;
                }).parent("tr").addClass(curVal[1]);
                if(curVal[0]!=undefined&&curVal[1]!=undefined)
                    $(".secondOne .table tbody>tr."+curVal[0]+'.'+curVal[1]).show();
                else if(curVal[0]==undefined && curVal[1]!=undefined)
                    $(".secondOne .table tbody>tr."+curVal[1]).show();
                else if(curVal[1]==undefined && curVal[0]!=undefined)
                    $(".secondOne .table tbody>tr."+curVal[0]).show();
                else
                    $(".secondOne .table tbody>tr").show();

            })
        })
    </script><?php } ?>
