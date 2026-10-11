<?php
$c = file_get_contents("resources/views/Pelanggan/Dashboard.blade.php");

// Try to use regex to keep only Item 1 and Item 4
$pattern = "/(<!-- Package Item 1 -->.*?<\/div>\s*<\/div>\s*<\/div>\s*)(<!-- Package Item 2 -->.*?)(<!-- Package Item 4 -->.*?<\/div>\s*<\/div>\s*<\/div>\s*)(<!-- Package Item 5 -->.*?<\/div>\s*<\/div>\s*<\/div>\s*<!-- END OF PACKAGES -->)?/s";

// Actually, it might be simpler to just replace the whole content inside <div class="d-flex flex-column gap-3 mb-4"> or similar container.

