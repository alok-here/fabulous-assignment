<?php 

function query_getData($conn, $query)
{
    $query_execute = mysqli_query($conn, $query);
    if (mysqli_num_rows($query_execute) > 0) {
        $data = array();
        while ($result = mysqli_fetch_array($query_execute, MYSQLI_ASSOC)) {
            $data[] = $result;
        }
        return $data;
    }
    return [];
}

function query_create($conn, $query)
{
    return $conn->query($query);
}