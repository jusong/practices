#include <stdio.h>  
#include <termios.h>  
#include <sys/ioctl.h>  
#include <signal.h>  
#include <unistd.h>  
  
static void  
pr_winsize(int fd){  
        struct winsize size;  
        if(ioctl(fd,TIOCGWINSZ,(char*)&size) < 0){  
                perror("ioctl");  
                return;  
        }  
        printf("%d rows, %d columns\n",size.ws_row, size.ws_col);  
}  
  
static void  
sig_winch(int signo){  
        printf("SIGWINCH received.\n");  
        pr_winsize(STDIN_FILENO);  
}  
  
int main(void){  
        if(isatty(STDIN_FILENO) == 0){  
                perror("isatty");  
                return -1;  
        }  
  
        if(signal(SIGWINCH,sig_winch) == SIG_ERR){  
                perror("signal");  
                return -1;  
        }  
  
        pr_winsize(STDIN_FILENO);  
        for(;;){  
                pause();  
        }  
}  
