<?php
function bersihkanInput($data)
{
    return htmlspecialchars(stripslashes(trim($data)));
}

$baris = isset($_POST['baris']) ? intval($_POST['baris']) : 0;
$kolom = isset($_POST['kolom']) ? intval($_POST['kolom']) : 0;
$data = isset($_POST['data']) ? $_POST['data'] : array();
$tampilan = 'awal';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['baris']) && isset($_POST['kolom']) && empty($data)) {
        if ($baris > 0 && $kolom > 0) {
            $tampilan = 'input_data';
        }
    } elseif (!empty($data)) {
        $tampilan = 'hasil';
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Form Input Data Tabel</title>
</head>

<body>
    <?php if ($tampilan === 'awal'): ?>

        <h2>Inputkan Jumlah Baris dan Kolom</h2>
        <form method="POST">
            <label for="baris">Inputkan jumlah Baris:</label>
            <input type="number" id="baris" name="baris" min="1" required><br><br>

            <label for="kolom">Inputkan jumlah Kolom:</label>
            <input type="number" id="kolom" name="kolom" min="1" required><br><br>

            <input type="submit" value="SUBMIT">
        </form>

    <?php elseif ($tampilan === 'input_data'): ?>

        <h2>Input Data</h2>

        <form method="POST">
            <input type="hidden" name="baris" value="<?php echo $baris; ?>">
            <input type="hidden" name="kolom" value="<?php echo $kolom; ?>">

            <table border="1" cellpadding="5" cellspacing="0">
                <?php for ($i = 1; $i <= $baris; $i++): ?>
                    <tr>
                        <?php for ($j = 1; $j <= $kolom; $j++): ?>
                            <td>
                                <label for="data_<?php echo $i . '_' . $j; ?>"><?php echo $i . ':' . $j; ?></label>
                                <input type="text" id="data_<?php echo $i . '_' . $j; ?>" name="data[<?php echo $i . '_' . $j; ?>]"
                                    required>
                            </td>
                        <?php endfor; ?>
                    </tr>
                <?php endfor; ?>
                <tr>
                    <td colspan="<?php echo $kolom; ?>" align="center">
                        <input type="submit" value="SUBMIT">
                    </td>
                </tr>
            </table>
        </form>

    <?php elseif ($tampilan === 'hasil'): ?>

        <h2>Hasil Input Data</h2>

        <?php
        for ($i = 1; $i <= $baris; $i++) {
            for ($j = 1; $j <= $kolom; $j++) {
                $key = $i . '_' . $j;
                if (isset($data[$key])) {
                    echo '<p>' . $i . '.' . $j . ': ' . bersihkanInput($data[$key]) . '</p>';
                }
            }
        }
        ?>

        <a href="<?php echo $_SERVER['PHP_SELF']; ?>">Kembali ke Form Awal</a>

    <?php endif; ?>
</body>

</html>