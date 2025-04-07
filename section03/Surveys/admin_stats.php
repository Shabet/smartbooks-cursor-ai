<?php
session_start();

// 로그인 체크
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: admin_login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>설문 통계</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <!-- 헤더 -->
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">설문 통계</h1>
            <div class="flex items-center space-x-4">
                <span class="text-gray-600">관리자님 환영합니다</span>
                <a href="admin_logout.php" class="text-red-500 hover:text-red-700">
                    <i class="fas fa-sign-out-alt mr-1"></i>로그아웃
                </a>
            </div>
        </div>

        <!-- 통계 카드 그리드 -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <!-- 총 응답 수 -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500">총 응답 수</p>
                        <h3 class="text-2xl font-bold text-gray-800" id="totalResponses">0</h3>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-full">
                        <i class="fas fa-users text-blue-500 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- 평균 만족도 -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500">평균 만족도</p>
                        <h3 class="text-2xl font-bold text-gray-800" id="avgSatisfaction">0</h3>
                    </div>
                    <div class="bg-green-100 p-3 rounded-full">
                        <i class="fas fa-smile text-green-500 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- 재방문 의향 -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500">재방문 의향</p>
                        <h3 class="text-2xl font-bold text-gray-800" id="returnRate">0%</h3>
                    </div>
                    <div class="bg-purple-100 p-3 rounded-full">
                        <i class="fas fa-undo text-purple-500 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- 차트 섹션 -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- 만족도 분포 -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">1. 전반적인 식사 경험 만족도</h2>
                <canvas id="satisfactionChart1"></canvas>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">2. 음식의 맛 만족도</h2>
                <canvas id="satisfactionChart2"></canvas>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">3. 음식의 양 만족도</h2>
                <canvas id="satisfactionChart3"></canvas>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">4. 음식의 온도 만족도</h2>
                <canvas id="satisfactionChart4"></canvas>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">5. 직원의 서비스 만족도</h2>
                <canvas id="satisfactionChart5"></canvas>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">6. 레스토랑의 청결도 만족도</h2>
                <canvas id="satisfactionChart6"></canvas>
            </div>

            <!-- 재방문 의향 -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">재방문 의향</h2>
                <canvas id="returnChart"></canvas>
            </div>

            <!-- 메뉴 선택 비율 -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">메뉴 선택 비율</h2>
                <canvas id="menuChart"></canvas>
            </div>
        </div>

        <!-- 주관식 응답 섹션 -->
        <div class="mt-8">
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">주관식 응답</h2>
                <div class="space-y-8">
                    <!-- 8번 문항 응답 -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-700 mb-3">8. 가격 대비 만족도에 대한 의견</h3>
                        <div class="space-y-4" id="subjectiveResponses8">
                            <!-- 8번 문항 응답이 여기에 동적으로 추가됩니다 -->
                        </div>
                    </div>

                    <!-- 9번 문항 응답 -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-700 mb-3">9. 레스토랑 분위기에 대한 의견</h3>
                        <div class="space-y-4" id="subjectiveResponses9">
                            <!-- 9번 문항 응답이 여기에 동적으로 추가됩니다 -->
                        </div>
                    </div>

                    <!-- 11번 문항 응답 -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-700 mb-3">11. 기타 의견</h3>
                        <div class="space-y-4" id="subjectiveResponses11">
                            <!-- 11번 문항 응답이 여기에 동적으로 추가됩니다 -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // 설문 데이터 가져오기
        async function loadSurveyData() {
            try {
                const response = await fetch('get_stats.php');
                const data = await response.json();
                updateStats(data);
                createCharts(data);
                displaySubjectiveResponses(data);
            } catch (error) {
                console.error('데이터 로딩 실패:', error);
            }
        }

        // 통계 업데이트
        function updateStats(data) {
            document.getElementById('totalResponses').textContent = data.totalResponses;
            document.getElementById('avgSatisfaction').textContent = data.avgSatisfaction.toFixed(1);
            document.getElementById('returnRate').textContent = data.returnRate + '%';
        }

        // 주관식 응답 표시
        function displaySubjectiveResponses(data) {
            // 8번 문항 응답 표시
            displaySubjectiveResponse('subjectiveResponses8', data.subjectiveResponses.question_8);
            // 9번 문항 응답 표시
            displaySubjectiveResponse('subjectiveResponses9', data.subjectiveResponses.question_9);
            // 11번 문항 응답 표시
            displaySubjectiveResponse('subjectiveResponses11', data.subjectiveResponses.question_11);
        }

        // 개별 주관식 응답 표시 함수
        function displaySubjectiveResponse(containerId, responses) {
            const container = document.getElementById(containerId);
            if (responses && responses.length > 0) {
                container.innerHTML = responses.map((response, index) => `
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-start">
                            <span class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center text-blue-500 font-semibold">
                                ${index + 1}
                            </span>
                            <p class="ml-4 text-gray-700">${response}</p>
                        </div>
                    </div>
                `).join('');
            } else {
                container.innerHTML = `
                    <div class="text-center text-gray-500 py-4">
                        아직 응답이 없습니다.
                    </div>
                `;
            }
        }

        // 차트 생성
        function createCharts(data) {
            // 만족도 분포 차트 (1-6번 문항)
            for (let i = 1; i <= 6; i++) {
                new Chart(document.getElementById(`satisfactionChart${i}`), {
                    type: 'bar',
                    data: {
                        labels: ['매우 불만족', '불만족', '보통', '만족', '매우 만족'],
                        datasets: [{
                            label: '응답 수',
                            data: data[`satisfactionDistribution${i}`],
                            backgroundColor: 'rgba(59, 130, 246, 0.5)',
                            borderColor: 'rgb(59, 130, 246)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            }

            // 재방문 의향 차트
            new Chart(document.getElementById('returnChart'), {
                type: 'pie',
                data: {
                    labels: ['재방문 의향 있음', '재방문 의향 없음'],
                    datasets: [{
                        data: [data.returnCount, data.totalResponses - data.returnCount],
                        backgroundColor: [
                            'rgba(34, 197, 94, 0.5)',  // 초록색
                            'rgba(239, 68, 68, 0.5)'   // 빨간색
                        ],
                        borderColor: [
                            'rgb(34, 197, 94)',
                            'rgb(239, 68, 68)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });

            // 메뉴 선택 비율 차트
            new Chart(document.getElementById('menuChart'), {
                type: 'pie',
                data: {
                    labels: data.menuLabels,
                    datasets: [{
                        data: data.menuData,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.5)',
                            'rgba(54, 162, 235, 0.5)',
                            'rgba(255, 206, 86, 0.5)',
                            'rgba(75, 192, 192, 0.5)'
                        ],
                        borderColor: [
                            'rgb(255, 99, 132)',
                            'rgb(54, 162, 235)',
                            'rgb(255, 206, 86)',
                            'rgb(75, 192, 192)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }

        // 페이지 로드 시 데이터 가져오기
        document.addEventListener('DOMContentLoaded', loadSurveyData);
    </script>
</body>
</html> 