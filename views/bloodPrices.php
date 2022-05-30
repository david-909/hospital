<table>
    <tr>
        <th>Naziv</th>
        <th>Cena</th>
    </tr>
    <?php
    include_once("data/connection.php");
    global $con;
    $output = "";
    $query = "SELECT * FROM bloods LIMIT 50";
    $result = $con->query($query)->fetchAll();
    if ($result) {
        foreach ($result as $r) {
            $output .= "<tr><td>" . $r->analysis . "</td><td>" . $r->price . "</td></tr>";
        }
        echo $output;
    }
    ?>
</table>