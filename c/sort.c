/*****************************************************************
 * 文件名称：sort.c
 * 创 建 者：blacknc <jusonlinux@163.com>
 * 创建日期：2018-05-15 12:42
 * 描    述：排序算法实现
 *****************************************************************/

#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <limits.h>
#include "sort.h"

/**
 * 冒泡排序
 */
void
bubblesort(int arr[], const int len)
{
    int i, j;

    for (i = 0; i < len - 1; i++)
    {
        for (j = len - 1; j > i; j--)
        {
            if (arr[j] < arr[j - 1])
            {
                swap(&arr[j - 1], &arr[j]);
            }
        }
    }
}

/**
 * 鸡尾酒冒泡排序
 */
void
cocktailsort(int arr[], const int len)
{
    int i, left, right;

    left = 0;
    right = len - 1;

    while (left < right)
    {
        for (i = left; i < right; i++)
        {
            if (arr[i] > arr[i + 1])
            {
                swap(&arr[i], &arr[i + 1]);
            }
        }
        right--;

        for (i = right; i > left; i--)
        {
            if (arr[i] < arr[i - 1])
            {
                swap(&arr[i], &arr[i - 1]);
            }
        }
        left++;
    }
}

/**
 * 选择排序
 */
void
selectsort(int arr[], const int len)
{
    int i, j;

    for (i = 0; i < len - 1; i++)
    {
        int min = len - 1;
        for (j = len - 2; j >= i; j--)
        {
            if (arr[j] < arr[min])
            {
                min = j;
            }
        }
        if (min != i)
        {
            swap(&arr[i], &arr[min]);
        }
    }
}

/**
 * 插入排序
 */
void
insertsort(int arr[], const int len)
{
    int i, j;
    for (i = 1; i < len; i++)
    {
        int get = arr[i];
        for (j = i - 1; j >= 0 && get < arr[j]; j--)
        {
            arr[j + 1] = arr[j];
        }
        arr[j + 1] = get;
    }
}

/**
 * 二分插入排序
 */
void
binaryinsertsort(int arr[], const int len)
{
    int i, j;
    for (i = 1; i < len; i++)
    {
        int get = arr[i];
        int left = 0;
        int right = i - 1;
        while (left <= right)
        {
            int mid = (right + left) / 2;
            if (get < arr[mid])
            {
                right = mid - 1;
            }
            else
            {
                left = mid + 1;
            }
        }
        for (j = i - 1; j >= left; j--)
        {
            arr[j + 1] = arr[j];
        }
        arr[left] = get;
    }
}

/**
 * 递减增量插入排序
 */
void
shellsort(int arr[], const int len)
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

/**
 * 快速排序，当元素低于5个时采用希尔排序
 */
void
myqsort(int arr[], const int len)
{
    sort_func_t sort_func = cocktailsort;
    //sort_func_t sort_func = shellsort;
    //sort_func_t sort_func = insertsort;
    //sort_func_t sort_func = binaryinsertsort;
    //sort_func_t sort_func = selectsort;
    //sort_func_t sort_func = bubblesort;

    if (len <= 5)
    {
        sort_func(arr, len);
        return;
    }

    int beginStack[sizeof(size_t) * CHAR_BIT];
    int endStack[sizeof(size_t) * CHAR_BIT];

    int i, j, begin, end, mid, loop;

    beginStack[0] = 0;
    endStack[0] = len - 1;

    for (loop = 0; loop >= 0; --loop)
    {
        begin = beginStack[loop];
        end = endStack[loop];

        while (end > begin)
        {
            i = begin + 1;
            j = end;

            mid = (end + begin) >> 1;
            swap(&arr[begin], &arr[mid]);

            while (1)
            {
                for (; i <= j && arr[i] <= arr[begin]; i++);
                for (; j >= i && arr[j] >= arr[begin]; j--);
                if (i >= j)
                {
                    break;
                }
                swap(&arr[i], &arr[j]);
                i++;
                j--;
            }
            swap(&arr[j], &arr[begin]);

            int flag1 = 0, flag2 = 0;
            if (j - begin < 5)
            {
                sort_func(arr + begin, j - begin);
                flag1 = 1;
            }
            if (end - j < 5)
            {
                sort_func(arr + j + 1, end - j);
                flag2 = 1;
            }

            if (flag1 == 1 && flag2 == 1)
            {
                break;
            }
            else if (flag1 == 0 && flag2 == 0)
            {
                if (j - begin < end - j)
                {
                    beginStack[loop] = begin;
                    endStack[loop++] = j - 1;
                    begin = j + 1;
                }
                else
                {
                    beginStack[loop] = j + 1;
                    endStack[loop++] = end;
                    end = j - 1;
                }
            }
            else if (flag1 == 1)
            {
                begin = j + 1;
            }
            else
            {
                end = j - 1;
            }
        }
    }
}

inline int
int_compar(const void *a, const void *b)
{
    return *(int *)a > *(int *)b ? 1 : (*(int *)a < *(int *)b ? -1 : 0);
}

inline void
myswap(void *a, void *b, size_t siz) {

    char *tmp_p = (char *)malloc(siz);
    if (NULL == tmp_p) {
        perror("malloc error");
        exit(-1);
    }

    memcpy(tmp_p, a, siz);
    memcpy(a, b, siz);
    memcpy(b, tmp_p, siz);
    free(tmp_p);
}

inline void
swap(int *a, int *b)
{
    int tmp = *a;
    *a = *b;
    *b = tmp;
}
