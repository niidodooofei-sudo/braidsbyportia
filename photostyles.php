<?php
header('Content-Type: text/css; charset=UTF-8');
header('Cache-Control: public, max-age=604800');
$base = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');
$p = $base . '/photos/';
$b = $base . '/';
?>
.tile { background-size: cover; background-position: center top; }
.tile:nth-child(1)  { background-image: url('<?= $p ?>beautiful-woman-with-braided-hair-posing-in-studio-2026-03-25-06-25-25-utc.JPG'); }
.tile:nth-child(2)  { background-image: url('<?= $p ?>black-woman-profile-face-and-hair-with-eyes-close-2026-03-25-02-15-01-utc.jpg'); background-position: center center; }
.tile:nth-child(3)  { background-image: url('<?= $p ?>braided-hair-style-woman-profile-outside-in-natur-2026-01-07-07-30-46-utc.jpg'); }
.tile:nth-child(4)  { background-image: url('<?= $p ?>child-profile-portrait-against-pink-background-2026-03-25-04-05-00-utc.jpg'); }
.tile:nth-child(5)  { background-image: url('<?= $p ?>close-up-portrait-of-a-young-black-woman-with-brai-2026-01-08-22-07-02-utc.jpg'); }
.tile:nth-child(6)  { background-image: url('<?= $p ?>cropped-shot-of-an-attractive-young-woman-posing-i-2026-01-09-09-28-19-utc.jpg'); }
.tile:nth-child(7)  { background-image: url('<?= $p ?>elegant-woman-with-braided-hair-in-studio-shot-2026-01-08-05-30-08-utc.jpg'); }
.tile:nth-child(8)  { background-image: url('<?= $p ?>elegant-woman-with-braided-hair-posing-in-studio-2026-03-10-02-07-29-utc.jpg'); }
.tile:nth-child(9)  { background-image: url('<?= $p ?>intense-portrait-with-long-braids-on-red-backdrop-2026-03-19-03-03-17-utc.jpg'); }
.tile:nth-child(10) { background-image: url('<?= $p ?>outdoor-head-and-shoulders-portrait-of-attractive-2026-03-10-03-16-35-utc.jpg'); }
.tile:nth-child(11) { background-image: url('<?= $p ?>rear-view-of-person-with-dreadlocks-2026-03-16-22-58-21-utc.jpg'); background-position: center center; }
.tile:nth-child(12) { background-image: url('<?= $p ?>rear-view-of-woman-with-braided-hair-2026-03-16-22-57-31-utc.jpg'); background-position: center center; }
.tile:nth-child(13) { background-image: url('<?= $p ?>rear-view-shot-of-black-woman-with-braids-and-drea-2026-03-17-07-15-46-utc.jpg'); background-position: center center; }
.tile:nth-child(14) { background-image: url('<?= $p ?>smiling-woman-wearing-braids-and-white-top-2026-01-09-00-34-36-utc.jpg'); }
.tile:nth-child(15) { background-image: url('<?= $p ?>stylish-person-with-long-flowing-locs-in-studio-2026-03-18-17-43-02-utc.jpg'); }
.tile:nth-child(16) { background-image: url('<?= $p ?>stylish-portrait-of-woman-with-long-braided-hair-2026-03-19-03-03-12-utc.jpg'); }
.tile:nth-child(17) { background-image: url('<?= $p ?>two-women-in-sunlight-wearing-earrings-outdoors-2026-03-25-05-26-29-utc.jpg'); }
.tile:nth-child(18) { background-image: url('<?= $p ?>two-young-women-with-braided-hair-and-earrings-2026-03-19-04-28-47-utc.jpg'); }
.tile:nth-child(19) { background-image: url('<?= $p ?>woman-poses-smiling-in-beauty-portrait-style-2026-03-20-02-12-40-utc.jpg'); }
.tile:nth-child(20) { background-image: url('<?= $p ?>woman-posing-on-colored-backgrounds-in-studio-wear-2026-03-24-04-05-41-utc.jpg'); }
.tile:nth-child(21) { background-image: url('<?= $p ?>woman-with-african-braids-wearing-top-crosses-the-2026-03-09-08-44-14-utc.jpg'); background-position: center center; }
.tile:nth-child(22) { background-image: url('<?= $p ?>woman-with-african-braids-wearing-top-crosses-the-2026-03-09-23-54-37-utc.jpg'); background-position: center center; }
.tile:nth-child(23) { background-image: url('<?= $p ?>women-in-black-tops-with-braided-hairstyles-2026-03-25-07-44-53-utc.jpg'); background-position: center center; }
.tile:nth-child(24) { background-image: url('<?= $p ?>intense-portrait-with-long-braids-on-red-backdrop-2026-03-19-03-03-17-utc.jpg'); }

.braid-pattern { background-image: url('<?= $b ?>pattern.svg'); }

.sh-img-quick       { background-image: url('<?= $p ?>beautiful-woman-with-braided-hair-posing-in-studio-2026-03-25-06-25-25-utc.JPG'); background-size: cover; background-position: center top; }
.sh-img-stitch      { background-image: url('<?= $p ?>close-up-portrait-of-a-young-black-woman-with-brai-2026-01-08-22-07-02-utc.jpg'); background-size: cover; background-position: center top; }
.sh-img-tribal      { background-image: url('<?= $p ?>rear-view-of-woman-with-braided-hair-2026-03-16-22-57-31-utc.jpg'); background-size: cover; background-position: center top; }
.sh-img-locs        { background-image: url('<?= $p ?>stylish-person-with-long-flowing-locs-in-studio-2026-03-18-17-43-02-utc.jpg'); background-size: cover; background-position: center top; }
.sh-img-box-braids  { background-image: url('<?= $p ?>smiling-woman-wearing-braids-and-white-top-2026-01-09-00-34-36-utc.jpg'); background-size: cover; background-position: center top; }
.sh-img-knotless    { background-image: url('<?= $p ?>elegant-woman-with-braided-hair-in-studio-shot-2026-01-08-05-30-08-utc.jpg'); background-size: cover; background-position: center top; }
.sh-img-french-curls{ background-image: url('<?= $p ?>intense-portrait-with-long-braids-on-red-backdrop-2026-03-19-03-03-17-utc.jpg'); background-size: cover; background-position: center top; }
.sh-img-twists      { background-image: url('<?= $p ?>woman-with-african-braids-wearing-top-crosses-the-2026-03-09-08-44-14-utc.jpg'); background-size: cover; background-position: center center; }

.g1 { background-image: url('<?= $p ?>outdoor-head-and-shoulders-portrait-of-attractive-2026-03-10-03-16-35-utc.jpg'); background-position: center top; }
.g2 { background-image: url('<?= $p ?>black-woman-profile-face-and-hair-with-eyes-close-2026-03-25-02-15-01-utc.jpg'); }
.g3 { background-image: url('<?= $p ?>elegant-woman-with-braided-hair-posing-in-studio-2026-03-10-02-07-29-utc.jpg'); background-position: center top; }
.g4 { background-image: url('<?= $p ?>rear-view-shot-of-black-woman-with-braids-and-drea-2026-03-17-07-15-46-utc.jpg'); }
.g5 { background-image: url('<?= $p ?>braided-hair-style-woman-profile-outside-in-natur-2026-01-07-07-30-46-utc.jpg'); background-position: center top; }
.g6 { background-image: url('<?= $p ?>woman-poses-smiling-in-beauty-portrait-style-2026-03-20-02-12-40-utc.jpg'); background-position: center top; }
.g7 { background-image: url('<?= $p ?>stylish-portrait-of-woman-with-long-braided-hair-2026-03-19-03-03-12-utc.jpg'); background-position: center top; }
.g8 { background-image: url('<?= $p ?>woman-posing-on-colored-backgrounds-in-studio-wear-2026-03-24-04-05-41-utc.jpg'); background-position: center top; }
.g9 { background-image: url('<?= $p ?>women-in-black-tops-with-braided-hairstyles-2026-03-25-07-44-53-utc.jpg'); }
