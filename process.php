<?php 
  $conn = new mysqli("localhost", "root", "123123", "desafio_stw");
  if($conn->connect_error){
    die("Conexão falhou!".$conn->connect_error);
  }
  
  $result = array('error'=>false);
  $action = '';

  if(isset($_GET['action'])) {
    $action = $_GET['action'];
  }

  //-----------------INGREDIENTES--------------------------

  if($action == 'createIngredient') {//create ingredientes
    $nameIngredient = $_POST['nameIngredient'];

    $sql = $conn->query("INSERT INTO ingredientes (descricao) VALUES('$nameIngredient')");

    if($sql) {
      $result['message'] = "Ingrediente adicionado com sucesso!";
    }
    else {
      $result['error'] = true;
      $result['message'] = "Erro ao adicionar ingrediente!";
    }
  }

  if($action == 'readIngredient') {//read ingredientes
    $sql = $conn->query("SELECT codigo, descricao FROM ingredientes");
    $ingredients = array();
    while($row = $sql->fetch_assoc()) {
      array_push($ingredients, $row);
    }
    $result['ingredients'] = $ingredients;
  }

  if($action == 'updateIngredient') {//update ingredientes
    $id = $_POST['id'];
    $nameIngredient = $_POST['nameIngredient'];

    $sql = $conn->query("UPDATE ingredientes SET descricao ='$nameIngredient' WHERE id ='$id'");

    if($sql) {
      $result['message'] = "Ingrediente atualizado com sucesso!";
    }
    else {
      $result['error'] = true;
      $result['message'] = "Erro ao atualizar ingrediente!";
    }
  }

  if($action == 'deleteIngredient') {//delete ingredientes

    $id = $_POST['id'];

    $sql = $conn->query("DELETE FROM ingredientes WHERE id='$id'");

    if($sql) {
      $result['message'] = "Ingrediente excluído com sucesso!";
    }
    else {
      $result['error'] = true;
      $result['message'] = "Erro ao excluir ingrediente!";
    }
  }

//--------------------------RECEITAS-------------------------

  if($action == 'createRecipe') {//falta fazer um for para armazenar mais de um ingrediente nas variaveis
    $nameRecipe = $_POST['nameRecipe'];
    $order = $_POST['order'];
    $ingredient = $_POST['ingredient'];
    $quantity = $_POST['quantity'];

    $sqlRecipe = $conn->query("INSERT INTO receitas (descricao) VALUES('$nameRecipe')");

    $idRecipe = $conn->query("SELECT MAX(codigo) as codigo FROM receitas");
    $idIngredient = $conn->query("SELECT codigo FROM ingredientes WHERE descricao = '$ingredient'");

    $sqlIngredient = $conn->query("INSERT INTO receitas_has_ingredientes (receitas_codigo, ingredientes_codigo, ordem, quantidade) VALUES('$idRecipe', '$idIngredient', '$order', '$quantity'");


    if($sqlRecipe && $sqlIngredient) {
      $result['message'] = "Receita adicionada com sucesso!";
    }
    else {
      $result['error'] = true;
      $result['message'] = "Erro ao adicionar receita!";
    }
  }

  if($action == 'readRecipes') {//read receitas
    $sql = $conn->query("SELECT codigo, descricao FROM receitas");
    $recipes = array();
    while($row = $sql->fetch_assoc()) {
      array_push($recipes, $row);
    }
    $result['recipes'] = $recipes;
  }

  if($action == 'readOneRecipe') {//read receita única
    $id = $_POST['id'];
    $sqlReceita = $conn->query("SELECT codigo, descricao FROM receitas WHERE codigo = '$id'");
    $sqlIngrediente = $conn->query("SELECT ingredientes.descricao, ordem, quantidade FROM receitas_has_ingredientes, ingredientes WHERE receitas_codigo = '$id'
    AND ingredientes.codigo = receitas_has_ingredientes.ingredientes_codigo");
    $recipes = array();
    while($row = $sqlReceita->fetch_assoc()) {
      array_push($recipes, $row);
    }
    while($row = $sqlIngrediente->fetch_assoc()) {
      array_push($recipes, $row);
    }
    $result['recipes'] = $recipes;
  }

  if($action == 'updateRecipe') {//update receitas
    $idRecipe = $_POST['idRecipe'];
    $nameRecipe = $_POST['nameRecipe'];
    $order = $_POST['order'];
    $ingredient = $_POST['ingredient'];
    $quantity = $_POST['quantity'];

    $sqlRecipe = $conn->query("UPDATE recipes SET descricao='$nameRecipe' WHERE id='$id'");

    $idIngredient = $conn->query("SELECT codigo FROM ingredientes WHERE descricao = '$ingredient'");

    $sqlIngredient = $conn->query("UPDATE receitas_has_ingredientes SET ingredientes_codigo = '$idIngredient', ordem = '$order', quantidade = '$quantity' WHERE receitas_codigo = '$idRecipe'");


    if($sqlRecipe && $sqlIngredient) {
      $result['message'] = "Receita editada com sucesso!";
    }
    else {
      $result['error'] = true;
      $result['message'] = "Erro ao editar receita!";
    }
  }

  if($action == 'deleteRecipe') {//delete ingredientes

    $id = $_POST['id'];

    $sqlRecipe = $conn->query("DELETE FROM receitas WHERE codigo='$id'");
    $sqlIngredients = $conn->query("DELETE FROM receitas_has_ingredientes WHERE receitas_codigo = '$id'");

    if($sqlRecipe && $sqlIngredient) {
      $result['message'] = "Receita excluída com sucesso!";
    }
    else {
      $result['error'] = true;
      $result['message'] = "Erro ao excluir receita!";
    }
  }

  $conn->close();
  echo json_encode($result);
?>