/*****************************************************************
 * 文件名称：sort.h
 * 创 建 者：blacknc <jusonlinux@163.com>
 * 创建日期：2018-05-15 16:59
 * 描    述：
 *****************************************************************/

#ifndef SORT_H
#define SORT_H

typedef int (*compar_func_t)(const void *, const void *);
typedef void (*sort_func_t)(int arr[], const int len);

int int_compar(const void *, const void *);
void myswap(void *, void *, size_t);
void swap(int *, int *);

//void myqsort(void *, size_t, size_t, compar_func_t);
void myqsort(int arr[], const int len);
void shellsort(int arr[], const int len);
void bubblesort(int arr[], const int len);
void cocktailsort(int arr[], const int len);
void selectsort(int arr[], const int len);
void insertsort(int arr[], const int len);
void binaryinsertsort(int arr[], const int len);

#endif
