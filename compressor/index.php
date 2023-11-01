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

    // Limitar o número máximo de imagens para 20
    $max_images = 20;
    $total_images = count($uploaded_images['name']);

    if ($total_images > $max_images) {
        die("<script>alert('Você pode fazer o upload de no máximo $max_images imagens de uma vez.');</script>");
    }

    // Qualidade da imagem (definida a partir do formulário)
    $qualidade_imagem = isset($_POST['qualidade']) ? (int)$_POST['qualidade'] : 70;

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

            // Salvar a imagem processada como WebP
            $output_path = $output_dir . pathinfo($image_name, PATHINFO_FILENAME) . ".webp";
            imagejpeg($imagem_processada, $output_path, $qualidade_imagem);

            $processed_images[] = $output_path;
        }
    }

    if (!empty($processed_images)) {
        // Gere um identificador único (timestamp) para o nome do arquivo ZIP
        $timestamp = time();
        $zip_filename = $timestamp . '.zip';

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
            echo "<script>alert('Erro ao criar o arquivo ZIP.');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="apple-touch-icon" sizes="180x180" href="assets/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/favicon-16x16.png">
    <link rel="manifest" href="assets/site.webmanifest">
    <title>Compressor</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.0.0/flowbite.min.css" rel="stylesheet" />
    <style>
        input[type="range"]::-webkit-slider-thumb {
            background: #7e22ce;
        }

        input[type="range"]::-moz-range-thumb {
            background: #7e22ce;
        }

        input[type="range"]::-ms-thumb {
            background: #7e22ce;
        }
    </style>
</head>

<body class="bg-gray-800 flex flex-col justify-center items-center h-screen">
    <h1 class="mb-4 text-white font-bold text-2xl">Compressor</h1>
    <main class="w-5/6 p-6 border rounded-sm shadow bg-gray-800 border-gray-700 md:w-2/3 flex flex-col md:flex-row gap-6">
        <form action="" method="post" enctype="multipart/form-data" class="grid gap-6 w-full md:w-2/3">

            <div>
                <label for="quality-range" class="block mb-2 text-sm font-medium text-white">Qualidade: <span id="selected-value">70</span>%</label>
                <input id="quality-range" type="range" name="qualidade" value="70" min="1" max="95" class="w-full h-2 bg-gray-700 rounded-sm appearance-none cursor-pointer">
            </div>

            <div>
                <label for="files" class="block mb-4 text-sm font-medium text-white">Selecione até 20 imagens para upload</label>
                <input class="block w-full text-lg text-gray-400 border border-gray-600 rounded-sm cursor-pointer bg-gray-700 focus:outline-none placeholder-gray-400" id="files" type="file" name="imagens[]" accept="image/*" multiple>
            </div>

            <!--
            <div class="flex items-center justify-center w-full">
                <label for="dropzone-file" class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-600 border-dashed rounded-sm cursor-pointer bg-gray-700 hover:bg-gray-600 dark:hover:border-gray-500">
                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                        <svg class="w-8 h-8 mb-4 text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                        </svg>
                        <p class="mb-2 text-sm text-gray-400"><span class="font-semibold">Clique para selecionar</span> ou arreste e solte</p>
                        <p class="text-xs text-gray-400">Selecione até 20 imagens para upload</p>
                    </div>
                    <input id="dropzone-file" type="file" class="hidden" name="imagens[]" accept="image/*" multiple/>
                </label>
            </div>
            -->

            <button type="submit" class="focus:outline-none text-white bg-purple-600 hover:bg-purple-700 focus:ring-4 focus:ring-purple-900 font-medium rounded-sm text-sm px-5 py-2.5 w-full">Enviar</button>
        </form>

        <div class="flex flex-col justify-center items-center gap-6 pt-6 md:pl-6 md:pt-0 w-full border-t border-gray-700 text-white md:w-1/3 md:border-t-0 md:border-l">
            <p class="text-sm text-center">E aí, gostou da compressão?</p>
            <a href="https://nubank.com.br/pagar/1b36f4/sJUPKL3uxb" target="_blank" type="button" class="border border-gray-600 focus:outline-none hover:bg-gray-700 focus:ring-4 focus:ring-purple-900 font-medium rounded-sm text-sm px-5 py-2.5 bg-gray-800 text-white hover:border-purple-600">Me pague um café! &#9749;</a>
        </div>
    </main>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.0.0/flowbite.min.js"></script>
    <script>
        const rangeInput = document.getElementById("quality-range");
        const selectedValue = document.getElementById("selected-value");

        rangeInput.addEventListener("input", function() {
            selectedValue.textContent = rangeInput.value;
        });
    </script>
</body>

</html>