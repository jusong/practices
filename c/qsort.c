/*****************************************************************
 * 文件名称：qsort.c
 * 创 建 者：blacknc <jusonlinux@163.com>
 * 创建日期：2018-02-27 13:31
 * 描    述：
 *****************************************************************/

#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <time.h>
#include <sys/time.h>
#include <limits.h>

typedef int (*compar_func_t)(const void *, const void *);

int int_compar(const void *, const void *);
void myqsort(void *, size_t, size_t, compar_func_t);
void myswap(void *, void *, size_t);

int
main(void) {

    int i, a[20], b[20], num = 20;
    struct timeval start_tm, end_tm;

    srand(time(NULL));
    for (i = 0; i < num; i++) {
        a[i] = rand() % 100;
        if (i < num) {
            printf("%d ", a[i]);
        }
    }
    printf("\n");

    memcpy(b, a, num * sizeof(int));
    gettimeofday(&start_tm, NULL);
    myqsort(b, num, sizeof(int), int_compar);
    gettimeofday(&end_tm, NULL);
    printf("myqsort cost time: %ldms\n", (end_tm.tv_sec - start_tm.tv_sec) * 1000 + (end_tm.tv_usec - end_tm.tv_usec) / 1000);
    for (i = 0; i < num; i++) {
        printf("%d ", b[i]);
    }
    printf("\n");

    memcpy(b, a, num * sizeof(int));
    gettimeofday(&start_tm, NULL);
    qsort(b, num, sizeof(int), int_compar);
    gettimeofday(&end_tm, NULL);
    printf("qsort cost time: %ldms\n", (end_tm.tv_sec - start_tm.tv_sec) * 1000 + (end_tm.tv_usec - end_tm.tv_usec) / 1000);
    for (i = 0; i < num; i++) {
        printf("%d ", b[i]);
    }
    printf("\n");

    return 0;
}

int
int_compar(const void *a, const void *b) {
    return *(int *)a > *(int *)b;
}

void myqsort(void *base, size_t nmemb, size_t siz, compar_func_t compar) {

    if (nmemb <= 1) {
        return;
    }

    int beginStack[sizeof(size_t) * CHAR_BIT];
    int endStack[sizeof(size_t) * CHAR_BIT];

    int i, j, begin, end, mid, loop;

    beginStack[0] = 0;
    endStack[0] = nmemb - 1;

    for (loop = 0; loop >=0; --loop) {
        //printf("loop times: %d\n", loop);
        begin = beginStack[loop];
        end = endStack[loop];

        while (end > begin) {
            mid = (end - begin + 1) >> 1;
            myswap(base + begin * siz, base + mid * siz, siz);

            i = begin + 1;
            j = end;
            while (1) {
                for (; i < j && compar(base + begin * siz, base + i * siz) > 0; i++);
                for (; j >= i && compar(base + j * siz, base + begin * siz) > 0; j--);
                if (i >= j) {
                    break;
                }
                myswap(base + i * siz, base + j * siz, siz);
                i++;
                j--;
            }
            myswap(base + begin * siz, base + j * siz, siz);
            int k;
            for (k = begin; k < j; k++) {
                printf("%d ", *(int *)(base + k * siz));
            }
            printf("<%d> ", *(int *)(base + j * siz));
            for (k = j + 1; k < end; k++) {
                printf("%d ", *(int *)(base + k * siz));
            }
            printf("\n");
            //myqsort(base, j, siz, compar);
            //myqsort(base + (j + 1) * siz,  nmemb - j - 1, siz, compar);
            if (j - begin < end - j) {
                if (j - begin > 1) {
                    beginStack[loop] = begin;
                    endStack[loop++] = j - 1;
                }
                begin = j + 1;
            } else {
                if (end - j > 1) {
                    beginStack[loop] = j + 1;
                    endStack[loop++] = end;
                }
                end = j - 1;
            }
        }
    }
}

void myswap(void *a, void *b, size_t siz) {

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
