/*****************************************************************
 * 文件名称：insertsort.c
 * 创 建 者：blacknc <jusonlinux@163.com>
 * 创建日期：2018-05-15 12:42
 * 描    述：插入排序
 *****************************************************************/

#include <stdio.h>

void
insertsort(int arr[], const int len)
{
    int h = 0;

    while (h < len)
    {
        h = 3 * h + 1;
    }

    while (h >= 1)
    {
        int i;
        for (i = h; i < len; i++)
        {
            int j = i - h;
            int get = arr[i];
            for (; j >=0 && arr[j] > get; j -= h)
            {
                arr[j + h] = arr[j];
            }
            arr[j + h] = get;
        }
        h = (h - 1) / 3;
    }
}

int
main(void)
{
    int arr[] = {10, 3, 456, 39, 2001, 308, 96, 1, 85, 38, 57, 9, 322, 335, 9543};
    int len = sizeof(arr) / sizeof(int);
    int i;

    for (i = 0; i < len; i++)
    {
        printf("%d ", arr[i]);
    }
    printf("\n");

    shellsort(arr, len);
    
    for (i = 0; i < len; i++)
    {
        printf("%d ", arr[i]);
    }
    printf("\n");

    return 0;
}
