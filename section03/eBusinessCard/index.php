<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>온라인 명함</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-slate-700 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-4xl w-full bg-gray-900 rounded-lg shadow-xl p-8">
        <!-- 상단 네비게이션 -->
        <nav class="flex justify-between items-center mb-12">
            <div class="flex items-center space-x-2">
                <div class="w-8 h-8 bg-blue-500 rounded-full"></div>
                <span class="text-white text-xl">Park Jun-yong</span>
            </div>
            <div class="space-x-6">
                <a href="#" class="text-white hover:text-gray-300"><i class="fas fa-user"></i> 소개</a>
                <a href="#" class="text-white hover:text-gray-300"><i class="fas fa-id-badge"></i> 약력</a>
                <a href="#" class="text-white hover:text-gray-300"><i class="fas fa-project-diagram"></i> 프로젝트</a>
                <a href="#" class="text-white hover:text-gray-300"><i class="fas fa-envelope"></i> 연락</a>
            </div>
        </nav>

        <!-- 프로필 섹션 -->
        <div class="flex flex-col md:flex-row items-center md:items-start space-y-6 md:space-y-0 md:space-x-8">
            <!-- 프로필 이미지 -->
            <div class="w-64 h-64 rounded-full overflow-hidden border-4 border-gray-700">
                <img src="https://randomuser.me/api/portraits/men/1.jpg" alt="Profile" class="w-full h-full object-cover">
            </div>

            <!-- 프로필 정보 -->
            <div class="flex-1">
                <div class="text-blue-500 text-xl mb-2">솔로</div>
                <h1 class="text-white text-4xl font-bold mb-2">Park Jun-yong</h1>
                <div class="text-gray-300 text-xl mb-4">박준용</div>
                <div class="text-gray-400 mb-6">길동에 거주</div>

                <!-- 소셜 미디어 아이콘 -->
                <div class="flex space-x-4">
                    <a href="#" class="w-10 h-10 bg-yellow-500 rounded-full flex items-center justify-center hover:opacity-80">
                        <i class="fas fa-comment text-white"></i>
                    </a>
                    <a href="#" class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center hover:opacity-80">
                        <i class="fab fa-linkedin-in text-white"></i>
                    </a>
                    <a href="#" class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center hover:opacity-80">
                        <i class="fab fa-facebook-f text-white"></i>
                    </a>
                    <a href="#" class="w-10 h-10 bg-pink-600 rounded-full flex items-center justify-center hover:opacity-80">
                        <i class="fab fa-instagram text-white"></i>
                    </a>
                    <a href="#" class="w-10 h-10 bg-black rounded-full flex items-center justify-center hover:opacity-80">
                        <i class="fab fa-x-twitter text-white"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- 비디오 섹션 -->
        <div class="mt-12">
            <h2 class="text-white text-2xl mb-6">Videos</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="aspect-video bg-gray-800 rounded-lg overflow-hidden">
                    <iframe width="100%" height="100%" src="https://www.youtube.com/embed/CHxzLiRRdMg" frameborder="0" allowfullscreen></iframe>
                    <div class="p-4">
                        <h3 class="text-white text-lg">콜로소 프롬프트 강의</h3>
                        <p class="text-gray-400">(2024.2)</p>
                    </div>
                </div>
                <div class="aspect-video bg-gray-800 rounded-lg overflow-hidden">
                    <iframe width="100%" height="100%" src="https://www.youtube.com/embed/vOn9S4zh1Qs" frameborder="0" allowfullscreen></iframe>
                    <div class="p-4">
                        <h3 class="text-white text-lg">MBC 다큐멘터리 출연분</h3>
                        <p class="text-gray-400">(2023.10)</p>
                    </div>
                </div>
                <div class="aspect-video bg-gray-800 rounded-lg overflow-hidden">
                    <iframe width="100%" height="100%" src="https://www.youtube.com/embed/Gt40VneLdX4" frameborder="0" allowfullscreen></iframe>
                    <div class="p-4">
                        <h3 class="text-white text-lg">온토리TV 출연분</h3>
                        <p class="text-gray-400">(2023.7)</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Books 섹션 -->
        <div class="mt-12">
            <h2 class="text-white text-2xl mb-6">Books</h2>
            <div class="grid grid-cols-1 md:grid-cols-6 gap-6">
                <?php
                // 가상 책 데이터 배열
                $books = [
                    ['title' => 'The Art of Strategy', 'link' => '#'],
                    ['title' => 'Philosophy of Life', 'link' => '#'],
                    ['title' => 'Tactics and Warfare', 'link' => '#'],
                    ['title' => 'Leadership in History', 'link' => '#'],
                    ['title' => 'The Way of the Warrior', 'link' => '#'],
                    ['title' => 'Ancient Wisdom', 'link' => '#']
                ];

                // 책 표지 및 링크 출력
                foreach ($books as $book) {
                    echo '<div class="bg-gray-800 rounded-lg overflow-hidden">
                            <img src="https://picsum.photos/150/100?random=' . rand(1, 1000) . '" alt="Book cover" class="w-full h-full object-cover">
                            <div class="p-4">
                                <h3 class="text-white text-lg">' . $book['title'] . '</h3>
                                <div class="text-blue-500 hover:underline">
                                    <a href="#">교보문고</a> |
                                    <a href="#">알라딘</a> |
                                    <a href="#">Yes24</a>
                                </div>
                            </div>
                          </div>';
                }
                ?>
            </div>
        </div>

        <!-- Careers 섹션 -->
        <div class="mt-12 grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Careers 섹션 -->
            <div>
                <h2 class="text-white text-2xl mb-6">Careers</h2>
                <div class="grid grid-cols-1 gap-6">
                    <div class="bg-gray-800 rounded-lg p-6">
                        <h3 class="text-white text-lg mb-2">의적 활동</h3>
                        <p class="text-gray-400">조선 시대의 의적 홍길동으로서 백성을 위한 활동을 펼쳤습니다.</p>
                    </div>
                    <div class="bg-gray-800 rounded-lg p-6">
                        <h3 class="text-white text-lg mb-2">백성의 영웅</h3>
                        <p class="text-gray-400">부패한 관리와 탐관오리를 물리치고 백성의 권리를 지켰습니다.</p>
                    </div>
                </div>
            </div>

            <!-- Education 섹션 -->
            <div>
                <h2 class="text-white text-2xl mb-6">Education</h2>
                <div class="grid grid-cols-1 gap-6">
                    <div class="bg-gray-800 rounded-lg p-6">
                        <h3 class="text-white text-lg mb-2">서당 교육</h3>
                        <p class="text-gray-400">한문과 유학을 중심으로 한 기초 교육을 받았습니다.</p>
                    </div>
                    <div class="bg-gray-800 rounded-lg p-6">
                        <h3 class="text-white text-lg mb-2">성균관</h3>
                        <p class="text-gray-400">조선 시대 최고의 교육 기관에서 수학하며 학문을 연마했습니다.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleTheme() {
            const html = document.documentElement;
            if (html.classList.contains('dark')) {
                html.classList.remove('dark');
                localStorage.setItem('theme', 'light');
            } else {
                html.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            }
        }

        // Load theme from local storage
        document.addEventListener('DOMContentLoaded', () => {
            if (localStorage.getItem('theme') === 'dark') {
                document.documentElement.classList.add('dark');
            }
        });
    </script>

    <style>
        .dark .bg-slate-700 { background-color: #f3f4f6; }
        .dark .bg-gray-900 { background-color: #ffffff; }
        .dark .text-white { color: #1f2937; }
        .dark .text-gray-300 { color: #4b5563; }
        .dark .text-gray-400 { color: #6b7280; }
        .dark .bg-gray-800 { background-color: #e5e7eb; }
    </style>

    <!-- 다크/라이트 모드 전환 버튼 -->
    <div class="fixed bottom-4 right-4">
        <button onclick="toggleTheme()" class="w-12 h-12 bg-gray-800 dark:bg-gray-200 rounded-full flex items-center justify-center shadow-lg hover:opacity-80">
            <i class="fas fa-adjust text-white dark:text-black"></i>
        </button>
    </div>
</body>
</html>
