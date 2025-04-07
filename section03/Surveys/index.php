<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>식당 이용 후기 설문지</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            200: '#bae6fd',
                            300: '#7dd3fc',
                            400: '#38bdf8',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                            800: '#075985',
                            900: '#0c4a6e',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gradient-to-br from-primary-50 to-primary-100 min-h-screen">
    <div class="container mx-auto px-4 py-12">
        <div class="max-w-3xl mx-auto">
            <!-- 헤더 섹션 -->
            <div class="text-center mb-12">
                <div class="inline-block p-4 bg-white rounded-full shadow-lg mb-6">
                    <i class="fas fa-utensils text-primary-600 text-4xl"></i>
                </div>
                <h1 class="text-4xl font-bold text-primary-800 mb-4">식당 이용 후기 설문지</h1>
                <p class="text-lg text-primary-600">고객님의 소중한 의견을 들려주시면 더 나은 서비스로 보답하겠습니다.</p>
            </div>

            <!-- 설문 폼 -->
            <div class="bg-white rounded-2xl shadow-xl p-8">
                <form id="surveyForm" class="space-y-10" method="POST" action="save_survey.php">
                    <!-- 설문 질문들이 여기에 동적으로 추가됩니다 -->
                </form>

                <div class="mt-10 text-center">
                    <button type="submit" form="surveyForm" 
                        class="bg-primary-600 hover:bg-primary-700 text-white font-bold py-3 px-8 rounded-lg transition duration-300 transform hover:scale-105 shadow-lg">
                        <i class="fas fa-paper-plane mr-2"></i>제출하기
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- 관리자 페이지 링크 -->
    <div class="fixed bottom-4 right-4">
        <a href="admin_login.php" class="text-xs text-gray-500 hover:text-gray-700">
            <i class="fas fa-user-shield mr-1"></i>관리자 페이지
        </a>
    </div>

    <script>
        // 설문 데이터를 가져오는 함수
        async function loadSurveyData() {
            try {
                const response = await fetch('survey.json');
                const surveyData = await response.json();
                renderSurvey(surveyData);
            } catch (error) {
                console.error('설문 데이터를 불러오는데 실패했습니다:', error);
            }
        }

        // 설문지를 렌더링하는 함수
        function renderSurvey(surveyData) {
            const form = document.getElementById('surveyForm');
            
            // 질문들을 순서대로 정렬
            const sortedQuestions = [...surveyData.questions].sort((a, b) => a.id - b.id);
            
            sortedQuestions.forEach(question => {
                const questionDiv = renderQuestion(question);
                form.appendChild(questionDiv);
            });
        }

        // 질문 렌더링 함수
        function renderQuestion(question) {
            const questionDiv = document.createElement('div');
            questionDiv.className = 'bg-white rounded-xl shadow-lg p-6 mb-6';
            questionDiv.id = `question-${question.id}`;

            let questionHtml = `
                <h3 class="text-lg font-semibold text-gray-800 mb-4">
                    ${question.id}. ${question.question}
                    ${question.required ? '<span class="text-red-500">*</span>' : ''}
                </h3>
            `;

            switch (question.type) {
                case 'rating':
                    questionHtml += `
                        <div class="flex flex-col space-y-2">
                            ${Array.from({length: question.max - question.min + 1}, (_, i) => i + question.min)
                                .map(num => `
                                    <label class="flex items-center space-x-3">
                                        <input type="radio" name="question_${question.id}" value="${num}" 
                                            class="w-5 h-5 text-blue-500" ${question.required ? 'required' : ''}>
                                        <span class="text-gray-700">${num}. ${question.labels[num]}</span>
                                    </label>
                                `).join('')}
                        </div>
                    `;
                    break;

                case 'multiple_choice':
                    questionHtml += `
                        <div class="space-y-3">
                            ${question.options.map(option => `
                                <label class="flex items-center">
                                    <input type="checkbox" name="question_${question.id}[]" value="${option}"
                                        class="w-5 h-5 text-blue-500 rounded" ${question.required ? 'required' : ''}>
                                    <span class="ml-3 text-gray-700">${option}</span>
                                </label>
                            `).join('')}
                        </div>
                    `;
                    break;

                case 'text':
                    questionHtml += `
                        <textarea name="question_${question.id}" 
                            class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                            rows="4" ${question.required ? 'required' : ''}></textarea>
                    `;
                    break;

                case 'yes_no':
                    questionHtml += `
                        <div class="flex space-x-6">
                            <label class="flex items-center">
                                <input type="radio" name="question_${question.id}" value="예" 
                                    class="w-5 h-5 text-blue-500" ${question.required ? 'required' : ''}>
                                <span class="ml-3 text-gray-700">예</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="question_${question.id}" value="아니오" 
                                    class="w-5 h-5 text-blue-500">
                                <span class="ml-3 text-gray-700">아니오</span>
                            </label>
                        </div>
                    `;
                    break;
            }

            questionDiv.innerHTML = questionHtml;
            return questionDiv;
        }

        // 페이지 로드 시 설문 데이터 불러오기
        document.addEventListener('DOMContentLoaded', loadSurveyData);

        // 폼 제출 처리
        document.getElementById('surveyForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            // AJAX로 데이터 전송
            fetch('save_survey.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                // 제출 완료 알림
                const notification = document.createElement('div');
                notification.className = 'fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg transform transition-all duration-500 opacity-0 translate-y-4';
                
                if (data.success) {
                    notification.className += ' bg-green-500 text-white';
                    notification.innerHTML = `
                        <div class="flex items-center">
                            <i class="fas fa-check-circle mr-2"></i>
                            <span>${data.message}</span>
                        </div>
                    `;
                    this.reset();
                } else {
                    notification.className += ' bg-red-500 text-white';
                    notification.innerHTML = `
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            <span>${data.message}</span>
                        </div>
                    `;
                }
                
                document.body.appendChild(notification);
                
                // 애니메이션 효과
                setTimeout(() => {
                    notification.classList.remove('opacity-0', 'translate-y-4');
                    setTimeout(() => {
                        notification.classList.add('opacity-0', 'translate-y-4');
                        setTimeout(() => notification.remove(), 500);
                    }, 3000);
                }, 100);
            })
            .catch(error => {
                console.error('Error:', error);
                alert('설문 제출 중 오류가 발생했습니다.');
            });
        });
    </script>
</body>
</html>
