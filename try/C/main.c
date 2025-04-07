#include <stdio.h>
#include <stdlib.h>
#include <math.h>

// Point 구조체 정의
typedef struct {
    int x;
    int y;
} Point;

// 기준점 (가장 아래쪽, 왼쪽 점)
Point p0;

// 두 점 사이의 거리 계산
int distSq(Point p1, Point p2) {
    return (p1.x - p2.x)*(p1.x - p2.x) + (p1.y - p2.y)*(p1.y - p2.y);
}

// 세 점의 방향을 결정 (반시계, 시계, 일직선)
int orientation(Point p, Point q, Point r) {
    int val = (q.y - p.y) * (r.x - q.x) - (q.x - p.x) * (r.y - q.y);
    if (val == 0) return 0;  // 일직선
    return (val > 0) ? 1 : 2; // 시계 또는 반시계
}

// qsort 비교 함수
int compare(const void *vp1, const void *vp2) {
    Point *p1 = (Point *)vp1;
    Point *p2 = (Point *)vp2;
    
    int o = orientation(p0, *p1, *p2);
    if (o == 0)
        return (distSq(p0, *p2) >= distSq(p0, *p1)) ? -1 : 1;
    
    return (o == 2) ? -1 : 1;
}

// 스택의 다음 상단 요소 반환
Point nextToTop(Point *stack, int top) {
    return stack[top-1];
}

// Graham Scan 알고리즘 구현
void convexHull(Point points[], int n) {
    // y 좌표가 가장 작은 점 찾기 (같으면 x 좌표가 작은 점)
    int ymin = points[0].y, min = 0;
    for (int i = 1; i < n; i++) {
        int y = points[i].y;
        if ((y < ymin) || (ymin == y && points[i].x < points[min].x))
            ymin = points[i].y, min = i;
    }
    
    // 찾은 점을 첫 번째 위치로 이동
    Point temp = points[0];
    points[0] = points[min];
    points[min] = temp;
    
    // 첫 번째 점을 기준점으로 설정
    p0 = points[0];
    
    // 나머지 점들을 기준점에 대한 각도로 정렬
    qsort(&points[1], n-1, sizeof(Point), compare);
    
    // 중복된 점 제거
    int m = 1;
    for (int i = 1; i < n; i++) {
        while (i < n-1 && orientation(p0, points[i], points[i+1]) == 0)
            i++;
        points[m] = points[i];
        m++;
    }
    
    if (m < 3) {
        printf("Convex hull이 존재하지 않습니다.\n");
        return;
    }
    
    // 스택 초기화
    Point *stack = (Point *)malloc(m * sizeof(Point));
    int top = -1;
    
    stack[++top] = points[0];
    stack[++top] = points[1];
    stack[++top] = points[2];
    
    // 나머지 점들 처리
    for (int i = 3; i < m; i++) {
        while (top > 0 && orientation(nextToTop(stack, top), stack[top], points[i]) != 2)
            top--;
        stack[++top] = points[i];
    }
    
    // 결과 출력
    printf("Convex Hull의 점들:\n");
    while (top >= 0) {
        Point p = stack[top--];
        printf("(%d, %d)\n", p.x, p.y);
    }
    
    free(stack);
}

int main() {
    // 테스트용 점들
    Point points[] = {
        {0, 3}, {1, 1}, {2, 2}, {4, 4},
        {0, 0}, {1, 2}, {3, 1}, {3, 3}
    };
    int n = sizeof(points)/sizeof(points[0]);
    
    convexHull(points, n);
    
    return 0;
}

