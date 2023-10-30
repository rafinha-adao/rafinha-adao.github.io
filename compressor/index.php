<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $upload_dir = "uploads/";
    $output_dir = "output/";

    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    if (!file_exists($output_dir)) {
        mkdir($output_dir, 0777, true);
    }

    $uploaded_images = $_FILES['imagens'];
    $processed_images = [];

    // Limitar o número máximo de imagens para 25
    $max_images = 25;
    $total_images = count($uploaded_images['name']);

    if ($total_images > $max_images) {
        die("Você pode fazer o upload de no máximo $max_images imagens de uma vez.");
    }

    // Qualidade da imagem (definida a partir do formulário)
    $qualidade_imagem = isset($_POST['qualidade']) ? (int)$_POST['qualidade'] : 60;

    function redimensionar_imagem($imagem, $largura_max, $altura_max)
    {
        list($largura_original, $altura_original) = getimagesize($imagem);
        $ratio = $largura_original / $altura_original;
        if ($largura_max / $altura_max > $ratio) {
            $largura = $altura_max * $ratio;
            $altura = $altura_max;
        } else {
            $largura = $largura_max;
            $altura = $largura_max / $ratio;
        }
        $imagem_redimensionada = imagecreatetruecolor($largura, $altura);
        $imagem_original = imagecreatefromjpeg($imagem);
        imagecopyresampled($imagem_redimensionada, $imagem_original, 0, 0, 0, 0, $largura, $altura, $largura_original, $altura_original);
        return $imagem_redimensionada;
    }

    function comprimir_imagem($imagem, $tamanho_max, $qualidade)
    {
        ob_start();
        imagejpeg($imagem, null, $qualidade);
        $imagem_comprimida = ob_get_contents();
        ob_end_clean();

        while (strlen($imagem_comprimida) > $tamanho_max) {
            $imagem = redimensionar_imagem($imagem, imagesx($imagem) * 0.9, imagesy($imagem) * 0.9);
            ob_start();
            imagejpeg($imagem, null, $qualidade);
            $imagem_comprimida = ob_get_contents();
            ob_end_clean();
        }

        return $imagem;
    }

    function converter_para_webp($imagem, $qualidade)
    {
        ob_start();
        imagewebp($imagem, null, $qualidade);
        $imagem_webp = ob_get_contents();
        ob_end_clean();

        return imagecreatefromwebp('data:image/webp;base64,' . base64_encode($imagem_webp));
    }

    foreach ($uploaded_images['name'] as $key => $image_name) {
        $target_file = $upload_dir . $image_name;
        $tmp_file = $uploaded_images['tmp_name'][$key];

        if (move_uploaded_file($tmp_file, $target_file)) {
            // Processar a imagem
            $imagem_processada = redimensionar_imagem($target_file, 1920, 1080);
            $imagem_processada = comprimir_imagem($imagem_processada, 1000 * 1024, $qualidade_imagem);
            $imagem_processada = converter_para_webp($imagem_processada, $qualidade_imagem);

            // Salvar a imagem processada
            $output_path = $output_dir . $image_name . ".webp";
            imagejpeg($imagem_processada, $output_path, $qualidade_imagem);

            $processed_images[] = $output_path;
        }
    }

    if (!empty($processed_images)) {
        // Gere um identificador único (timestamp) para o nome do arquivo ZIP
        $timestamp = time();
        $zip_filename = 'imagens_processadas_' . $timestamp . '.zip';

        $zip = new ZipArchive;
        if ($zip->open($zip_filename, ZipArchive::CREATE) === true) {
            foreach ($processed_images as $image_path) {
                $zip->addFile($image_path, basename($image_path));
            }
            $zip->close();

            // Envia o arquivo ZIP para download
            header('Content-Type: application/zip');
            header("Content-Disposition: attachment; filename=$zip_filename");
            header('Content-Length: ' . filesize($zip_filename));
            readfile($zip_filename);

            // Remove o arquivo ZIP após o download
            unlink($zip_filename);

            exit;
        } else {
            echo 'Erro ao criar o arquivo ZIP.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compressor</title>
</head>

<body>
    <form action="" method="post" enctype="multipart/form-data">
        <label for="imagens">Selecione até 25 imagens para upload:</label>
        <input type="file" name="imagens[]" accept="image/*" multiple>
        <label for="qualidade">Qualidade (1-100):</label>
        <input type="number" name="qualidade" id="qualidade" min="1" max="100" value="60">
        <input type="submit" value="Processar imagens">
    </form>
</body>

</html>