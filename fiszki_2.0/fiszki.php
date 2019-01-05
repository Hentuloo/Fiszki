<?php
include('baza.php');//sprawdzam połączenie
if(!$connect){
    header('location: index.php');//przekierowanie do logowania
}
?>
<!DOCTYPE html>
<html lang="en">
<?php include('header.php') ?>

<body>
    <div class="select">
        <?php 
        $query = "SELECT table_name  FROM information_schema.tables where table_schema='fiszki_nauka_slowek'";//wyszukuje nazwy tabel z db
       $result=mysqli_query($connect,$query); 
        $count = 0;
        while ($row = mysqli_fetch_array($result)) {
        echo '<button>'.$row[0]."</button>";
            $count++;
        }
    if($count == 0){//gdy nie ma żadnej tabeli dodaj fiszki
        $query = "CREATE TABLE fiszki_nauka_slowek.fiszki ( id INT NOT NULL AUTO_INCREMENT ,  v1 VARCHAR(70) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,  v2 VARCHAR(70) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,  weight INT NOT NULL ,    PRIMARY KEY  (id)) ENGINE = InnoDB";
        $result=mysqli_query($connect,$query);
        //wyświetlanie przedchwilo dodanej tabeli fiszki
        $query = "SELECT table_name  FROM information_schema.tables where table_schema='fiszki_nauka_slowek'";
       $result=mysqli_query($connect,$query); 
        $count = 0;
        while ($row = mysqli_fetch_array($result)) {
        echo '<button>'.$row[0]."</button>";
            $count++;
        }
    }
    
        ?>
    </div>
    <div class="panel"><span></span><span></span></div>
    <a class="log_out" href="table_settings/log_out.php">Wylogój</a>
    <div class="random_button"><i class="icon-arrows-ccw"></i></div>
    <div class="menu">
        <div class="menubutton"><i class="icon-cog-alt"></i></div>
        <div class="menu-content">
            <div>
                <h1>Operacje na słówkach</h1>

                <?php
        $result=mysqli_query($connect,$query);//wyszukuje nazwy tabel z db
        while ($row = mysqli_fetch_array($result)) {
        echo '<label><input type="radio" name="word_operation" value="'.$row[0].'" checked>'.$row[0].'</label>';
        }
               ?>
                <br>
                <div><h2>DODAĆ</h2>
                Wartość 1:<input type="text" name="add-v1">
                Wartość 2:<input type="text" name="add-v2"><br>
                <button class="add">DODAJ</button>
                </div>
                 <div><h2>Zaktualizować</h2>
                ID=<input type="text" name="update-id"><br>
                Wartość 1:<input type="text" name="update-v1">
                Wartość 2:<input type="text" name="update-v2"><br>
                <button class="update">Zaktualizować</button>
                </div>
                 <div><h2>Usunąć</h2>
                ID=<input type="text" name="delete-id">
                <button class="delete">Usunąć</button>
                <h2>Pokazać</h2>
               ID=<input type="text" name="select-id">
                <button class="select">Pokaż</button>
                </div>
                <h2>Rezultat:</h2>
                <div class="result">
                   
                    
                </div>
            </div>
            <div>
                <h1>Dodawanie nowej tabeli</h1>
                <form action="table_settings/add_db.php" method="post">
                Nazwa nowej tabeli:<input type="text" name="databasename">
                <input class="submit" type="submit" value="Wyślij">
                </form>
                </div><div>
                 <h1>Usuwanie tabel</h1>
                <form action="table_settings/delete_db.php" method="post">
                 <?php
        $result=mysqli_query($connect,$query);
        while ($row = mysqli_fetch_array($result)) {
        echo '<label><input type="radio" name="word_operation" value="'.$row[0].'" checked>'.$row[0].'</label>';
        }
                    ?>
                    <input class="submit" type="submit" value="Wyślij">
                    </form>
                    <a href="table_settings/log_out.php">WYLOGUJ</a>
            </div>
        </div>
    </div>
</body>
<script>
    const menu_click = document.querySelector('.menubutton').addEventListener('click', (e) => {
        document.querySelector('.menu').classList.toggle('active');
    });

</script>
<script>
    let array1=[]
    let array2=[]
    let weight=[]
    $(document).ready(function() {
        $('.select button').click(function(){
            $('.select button').removeClass('active')
            console.log(this.classList.add('active'))
            array1=[];
            array2=[];
            weight=[];
            const table=this.innerHTML
            $('.panel').load('table_settings/load_table_fiszki.php',{
                table:table
            })
        })
// DODAWANIE //
        $('.add').click(function() {
           let select_database = $("input[name=word_operation]:checked").val()
           let value1 = $("input[name=add-v1]").val()
           let value2 = $("input[name=add-v2]").val()
          if(value1&&value2){
              $('.result').load("table_settings/insert.php", {
            value1:value1,
            value2:value2,
            select_database:select_database
        });
          }
            else{
                console.log('error')
            }
        })
     // Aktualizowanie //   
        $('.update').click(function() {
           let select_database = $("input[name=word_operation]:checked").val()
           let value1 = $("input[name=update-v1]").val()
           let value2 = $("input[name=update-v2]").val()
           let id = $("input[name=update-id]").val()
          if(value1&&value2&&id){
              $('.result').load("table_settings/update.php", {
                  id:id,
            value1:value1,
            value2:value2,
            select_database:select_database
        });
          }
            else{
                console.log('error')
            }
        })
        // Usuwanie //   
        $('.delete').click(function() {
           let select_database = $("input[name=word_operation]:checked").val()
           let id = $("input[name=delete-id]").val()
          if(id){
              $('.result').load("table_settings/delete.php", {
                  id:id,
            select_database:select_database
        });
          }
            else{
                console.log('error')
            }
        })
          //Pokazać//   
        $('.select').click(function() {
           let select_database = $("input[name=word_operation]:checked").val()
           let id = $("input[name=select-id]").val()
          if(id){
              $('.result').load("table_settings/select.php", {
                  id:id,
            select_database:select_database
        });
          }
            else{
                console.log('error')
            }
        })
     
    })
    let random_language= 0;
    let random_words= 0;
     let second_word = false;//sprawdza czy jest pokazane drugie słówko
    const panel = document.querySelector('.panel')
    
    

    
    
    
    
    
    
    
    
    
    
    const losuj =()=>{ 
    if(array1.length>0 && second_word == false){
        panel.classList.remove('active')
        random_language=Math.floor(Math.random()*2);
        random_words = Math.floor(Math.random()*array1.length)
        if(random_language){
        panel.innerHTML=`<span>${array1[random_words]}</span><span>${array2[random_words]}</span>`
        }
        else{
        panel.innerHTML=`<span>${array2[random_words]}</span><span>${array1[random_words]}</span>` 
        }
        second_word=!second_word
    }
        else if(array1.length>0 && second_word == true){
            panel.classList.add('active')
            second_word=!second_word
        }
    }
//po kliknięciu w klawiature albo przycisk losuj słówko
    
document.addEventListener('keydown',losuj)
 document.querySelector('.random_button').addEventListener('click',losuj)
    

</script>

</html>
