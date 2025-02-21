<?php
require 'db.php';

// Process name submission
$current_name = $_POST['name'] ?? '';
?>
<!DOCTYPE html>
<html>
<head>
    <style>
        .gallery { display: grid; gap: 20px; }
        .photo-container { position: relative; }
        .photo { max-width: 800px; height: auto; }
        .marker { 
            position: absolute; 
            color: red;
            font-weight: bold;
            pointer-events: none;
        }
        form { margin-bottom: 20px; }
    </style>
</head>
<body>
    <form method="POST">
        Your Name: <input type="text" name="name" required value="<?= htmlspecialchars($current_name) ?>">
        <button type="submit">Set Name</button>
    </form>

    <div class="gallery">
    <?php
    $photos = glob('original/*.{jpg,jpeg,png,gif}', GLOB_BRACE);
    foreach($photos as $photoPath) {
        $photo = basename($photoPath);
        $resized = "resized/$photo";

        // Resize image if not cached
        if(!file_exists($resized)) {
            list($width, $height) = getimagesize($photoPath);
            $ratio = 800 / $width;
            $newHeight = $height * $ratio;

            $src = imagecreatefromstring(file_get_contents($photoPath));
            $dst = imagescale($src, 800, $newHeight);
            imagejpeg($dst, $resized);
            imagedestroy($src);
            imagedestroy($dst);
        }

        // Get markers from database
        $stmt = $pdo->prepare("SELECT x, y, name FROM markers WHERE photo = ?");
        $stmt->execute([$photo]);
        $markers = $stmt->fetchAll();

        // Output photo with markers
        echo "<div class='photo-container'>";
        echo "<img src='$resized' class='photo' data-photo='$photo'>";
        foreach($markers as $m) {
            $x = $m['x']; // Scale to resized image
            $y = $m['y'];
            echo "<div class='marker' style='left: {$x}px; top: {$y}px;'>X ({$m['name']})</div>";
        }
        echo "</div>";
    }
    ?>
    </div>

    <script>
    document.querySelectorAll('.photo').forEach(img => {
        img.addEventListener('click', async (e) => {
            const name = document.querySelector('[name="name"]').value;
            if(!name) return alert('Set your name first');

            const rect = img.getBoundingClientRect();
            const scaleX = img.naturalWidth / rect.width;
            const scaleY = img.naturalHeight / rect.height;

            const x = Math.round((e.clientX - rect.left) * scaleX);
            const y = Math.round((e.clientY - rect.top) * scaleY);

            const formData = new FormData();
            formData.append('photo', img.dataset.photo);
            formData.append('x', x);
            formData.append('y', y);
            formData.append('name', name);

            try {
                const response = await fetch('markers.php', {
                    method: 'POST',
                    body: formData
                });
                location.reload();
            } catch(e) {
                alert('Error saving marker');
            }
        });
    });
    </script>
</body>
</html>
