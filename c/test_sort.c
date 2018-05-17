/*****************************************************************
 * 文件名称：test_sort.c
 * 创 建 者：blacknc <jusonlinux@163.com>
 * 创建日期：2018-05-15 12:42
 * 描    述：测试排序算法
 *****************************************************************/

#include <stdio.h>
#include "sort.h"

void print_arr(int arr[], int len);

int
main(void)
{
    int arr[] = {10, 1, 2, 44, 57, 2335, 578, 5566, 2, 4546, 0, 567, 833, 2825, 8, 893, 456, 39, 2001, 308, 96};
    int len = sizeof(arr) / sizeof(int);
    int i;

    print_arr(arr, len);

    //shellsort(arr, len);
    //myqsort(arr, len);
    //cocktailsort(arr, len);
    //insertsort(arr, len);
    //bubblesort(arr, len);
    //selectsort(arr, len);
    binaryinsertsort(arr, len);

    print_arr(arr, len);
    
    return 0;
}

void
print_arr(int arr[], int len)
{
    int i;
    for (i = 0; i < len; i++)
    {
        printf("%d ", arr[i]);
    }
    printf("\n");
}
