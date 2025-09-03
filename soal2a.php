<?php
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'tes_teramedik';

$koneksi = mysqli_connect($host, $user, $password, $database);

if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

function bersihkanInput($data)
{
    return htmlspecialchars(stripslashes(trim($data)));
}

$keyword = '';
$whereClause = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['keyword'])) {
    $keyword = bersihkanInput($_POST['keyword']);
    if (!empty($keyword)) {
        $whereClause = "WHERE h.hobi LIKE '%" . mysqli_real_escape_string($koneksi, $keyword) . "%'";
    }
}

$query = "SELECT h.hobi, COUNT(DISTINCT h.person_id) as jumlah_person
          FROM hobi h
          $whereClause
          GROUP BY h.hobi
          ORDER BY jumlah_person DESC, h.hobi ASC";

$result = mysqli_query($koneksi, $query);

if (!$result) {
    die("Query error: " . mysqli_error($koneksi));
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Hobi dan Jumlah Person</title>
</head>

<body>
    <h1>Laporan Hobi dan Jumlah Person</h1>

    <form method="POST">
        <input type="text" name="keyword" placeholder="Cari berdasarkan hobi..." value="<?php echo $keyword; ?>">
        <button type="submit">Cari</button>
    </form>
    <br>

    <?php if (mysqli_num_rows($result) > 0): ?>
        <table border="1" cellpadding="5" cellspacing="0">
            <thead>
                <tr>
                    <th>Hobi</th>
                    <th>Jumlah Person</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo $row['hobi']; ?></td>
                        <td align="center"><?php echo $row['jumlah_person']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <p>Total <?php echo mysqli_num_rows($result); ?> hobi ditemukan.</p>

    <?php else: ?>
        <p>Tidak ada data hobi yang ditemukan.</p>
    <?php endif; ?>

    <?php
    mysqli_free_result($result);
    mysqli_close($koneksi);
    ?>
</body>

</html>