import sys
from PIL import Image

img = Image.open(sys.argv[1]).convert('YCbCr')  
w, h = img.size  
data = img.getdata()
cnt = 0  
for i, ycbcr in enumerate(data):  
    y, cb, cr = ycbcr  
    if 86 <= cb <= 117 and 140 <= cr <= 168:  
        cnt += 1  
print '%s %s porn picture.'%('this', 'is' if cnt > w * h * 0.1 else 'not is')
