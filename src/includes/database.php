<?php
if ( ! defined("_INCODE") ) die("Access Denied....!");

function query ($sql, $data = [], $statementStatus=false){
    global $conn;
    $query = false;
    try{
        $statement = $conn->prepare($sql);

        if(empty($data)){
            $query = $statement->execute();
        }else{
            $query = $statement->execute($data);
        }
    }catch(Exception $e){
        echo $e->getMessage() . '<br>';
        echo 'File: ' . $e->getFile() .' :Line: '. $e->getLine();
    }

    if($statementStatus && $query){
        return $statement;
    }

    return $query;
}

function insert($table, $dataInsert){
    $keyArr = array_keys($dataInsert);
    $fieldStr = implode(',', $keyArr);
    $valueStr = ':'.implode(', :', $keyArr);

    $sql = "INSERT INTO $table($fieldStr) VALUES ($valueStr)";

    return query($sql, $dataInsert);
}

function update($table, $dataUpdate = [], $condition = ''){
    $updateStr = '';
    foreach($dataUpdate as $key => $value){
        $updateStr .= $key .'=:'. $key .', ';
    }
    $updateStr = rtrim($updateStr,', ');

    if (!empty($condition)){
        $sql = "UPDATE $table SET $updateStr WHERE $condition";
    }else{
        $sql = "UPDATE $table SET $updateStr ";
    } 
    return query($sql, $dataUpdate);
}

function destroy($table, $condition=""){
    $sql = 'DELETE FROM ' . $table;
    if(!empty($condition)){
        $sql .= ' WHERE '. $condition;
    }

    return query($sql);
}

//Lấy dữ liệu từ câu lệnh sql
function getRaw($sql){
    $statement = query($sql, [], true);

    if(is_object($statement)){
        $fetchArr = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $fetchArr;
    }
    return false;
}

//Lấy 1 bản ghi
function firstRaw($sql){
    $statement = query($sql, [], true);

    if(is_object($statement)){
        $fetchArr = $statement->fetch(PDO::FETCH_ASSOC);
        return $fetchArr;
    }
    return false;
}

function get($table, $field='*', $condition = ''){
    $sql = "SELECT $field FROM $table";
    if(!empty($condition)){
        $sql.= " WHERE $condition";
    }
    return getRaw($sql);
}

function first($table, $field='*', $condition = ''){
    $sql = "SELECT $field FROM $table";
    if(!empty($condition)){
        $sql.= " WHERE $condition";
    }
    return firstRaw($sql);
}

function getRows($sql){
    $statement = query($sql, [], true);
    if(is_object($statement)){
        $count = $statement->rowCount();
    }
    return $count;
}
