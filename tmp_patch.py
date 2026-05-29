# -*- coding: utf-8 -*-
from pathlib import Path
replacements = {
    'frontend/assets/css/style.css': [
        ("/* Google Fonts */\n@import url('https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@300;400;500;600;700;800&display=swap');\n@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600;700&display=swap');",
         "/* Unified Futura font family */\n/* Using local Futura / fallback fonts */"),
        ("    font-family: 'Be Vietnam Pro', -apple-system, BlinkMacSystemFont, sans-serif;",
         "    font-family: 'Futura', 'Futura PT', 'Trebuchet MS', Arial, sans-serif;"),
        ("font-family: 'Playfair Display', serif;",
         "font-family: 'Futura', 'Futura PT', 'Trebuchet MS', Arial, sans-serif;")
    ],
    'frontend/assets/css/impeccable.css': [
        ("body{font-family: 'Be Vietnam Pro', system-ui, -apple-system, 'Segoe UI', 'Helvetica Neue', sans-serif;}",
         "body{font-family: 'Futura', 'Futura PT', 'Trebuchet MS', Arial, sans-serif;}"),
        ("font-family:'Playfair Display', serif;",
         "font-family: 'Futura', 'Futura PT', 'Trebuchet MS', Arial, sans-serif;")
    ],
    'frontend/assets/css/admin.css': [
        ("@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');",
         "/* Futura is used by default; no external font import needed */"),
        ("    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;",
         "    font-family: 'Futura', 'Futura PT', 'Trebuchet MS', Arial, sans-serif;")
    ],
    'frontend/assets/js/main.js': [
        ("                showToast('Đã xóa khỏi yêu thích', 'info');",
         "                showToast('Removed from favorites', 'info');"),
        ("                showToast('Đã thêm vào yêu thích', 'success');",
         "                showToast('Added to favorites', 'success');"),
        ("                modal.querySelector('.qv-description').textContent = book.description || 'Không có mô tả';",
         "                modal.querySelector('.qv-description').textContent = book.description || 'No description available';"),
        ("        btn.innerHTML = '<i class=\"fas fa-spinner fa-spin\"></i> Đang thêm...';",
         "        btn.innerHTML = '<i class=\"fas fa-spinner fa-spin\"></i> Adding...';"),
        ("            showToast('Đã thêm vào giỏ hàng', 'success');",
         "            showToast('Added to cart', 'success');"),
        ("            showToast(result.message || 'Không thể thêm vào giỏ', 'error');",
         "            showToast(result.message || 'Could not add to cart', 'error');"),
        ("        showToast('Đã xảy ra lỗi', 'error');",
         "        showToast('An error occurred', 'error');"),
        ("        showToast('Áp dụng mã giảm giá thành công!', 'success');",
         "        showToast('Discount code applied successfully!', 'success');"),
        ("        showToast(result.message || 'Mã giảm giá không hợp lệ', 'error');",
         "        showToast(result.message || 'Invalid discount code', 'error');"),
        ("    return new Intl.NumberFormat('vi-VN').format(amount) + 'đ';",
         "    return new Intl.NumberFormat('en-US').format(amount) + 'đ';")
    ]
}
for path, reps in replacements.items():
    p = Path(path)
    text = p.read_text(encoding='utf-8')
    for old, new in reps:
        if old not in text:
            print(f'MISSING: {path}: {old}')
        text = text.replace(old, new)
    p.write_text(text, encoding='utf-8')
print('done')
