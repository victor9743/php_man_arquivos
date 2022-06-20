<?php
    //  criando uma conexão com o banco PDO MYSQL
    $conn = new PDO("mysql:dbname=intranet; host=localhost", "root", "" );

    // 655 cartorios
    $cartorios = $conn->prepare("SELECT cc.idcartorios, cad.descricao, cc.codTj, cc.nome, cc.C_IdContribuicoes, cc.Tabnome FROM cadcartorios cc
    JOIN cadcidades cad ON  cad.idCidades = cc.endCidade;");
    $cartorios->execute();


    // cidades
    $cidades = $conn->prepare("SELECT * FROM cadcidades WHERE uf = 'CE'");
    $cidades->execute();
    //$results = $cidades->fetchAll();
    $results = $cartorios->fetchAll();
    //var_dump($results);

    function situacao($tipo, $id) {
        
        if($tipo == 1){
            //tj
            $conn = new PDO("mysql:dbname=intranet; host=localhost", "root", "" );
            $valor = $conn->prepare("SELECT CT.Valor FROM cadcartorios CC JOIN finconttj_cartorios CT ON CT.V_Idcartorios = CC.IdCartorios WHERE CC.IdCartorios = ".$id." ORDER BY periodo DESC ;");
            
            $valor->execute();
            $res = [];
            $res =  $valor->fetchAll();
          
            if (!isset($res[0])) return "Sem Associado";
            
            if ($res[0]["Valor"] > 0) {
                return "Adimplente";
            } else {
                return "Inadimplente";
            }
        } else {
            //anoreg
            $conn = new PDO("mysql:dbname=intranet; host=localhost", "root", "" );
            $valor = $conn->prepare("SELECT CT.ValorPago FROM cadcartorios CC JOIN finboletos CT ON CT.B_Idcartorios = CC.IdCartorios WHERE CC.IdCartorios = ".$id." ORDER BY CT.dataDocumento DESC ;");
            $valor->execute();
            $res = [];
            $res =  $valor->fetchAll();
            if (!isset($res[0])) return "Sem Associado";
            
            if ($res[0]["ValorPago"] > 0) {
                return "Adimplente";
            } else {
                return "Inadimplente";
            }
        }
 
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <table class="table" border="1">
        <thead>
            <!--<th>Cidade</th>-->
            <!--<th>Cartorios</th>-->
            <!--<th>Tipo Cotrib</th>-->
            <!--<th>Situacao</th>-->
            <th>Associados</th>
        </thead>
        <tbody>
            <?php for($i = 0; $i< count($results); $i++) {?>              
                <tr>
                    <!--<td><?#= $results[$i]["descricao"]?></td>-->
                    <!--<td><?#= $results[$i]["codTj"]?> - <?#= $results[$i]["nome"]?></td>-->
                    <!--<td><?#= $results[$i]["C_IdContribuicoes"] == 1 ? "Contrib TJ" : "Contrib Bol"?></td>-->
                    <!--<td><?#= situacao($results[$i]["C_IdContribuicoes"], $results[$i]["idcartorios"]); ?></td>-->
                    <td><?= situacao($results[$i]["C_IdContribuicoes"], $results[$i]["idcartorios"])== "Sem Associado"? 'ZZZ': $results[$i]["Tabnome"]?></td>
                </tr>
                
            <?php } ?>
        </tbody>
    </table>
    
</body>
</html>