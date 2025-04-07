<?php
header('Content-Type: application/json; charset=utf-8');

try {
    // POST 데이터 처리
    $responses = [];
    foreach ($_POST as $key => $value) {
        // 다중 선택인 경우 (question_7[]와 같은 형식)
        if (strpos($key, '[]') !== false) {
            $questionId = str_replace('[]', '', $key);
            // 값이 배열이 아닌 경우 배열로 변환
            $responses[$questionId] = is_array($value) ? $value : [$value];
        } else {
            $responses[$key] = $value;
        }
    }

    // 응답 데이터 저장
    $data = [
        'timestamp' => date('Y-m-d H:i:s'),
        'responses' => $responses
    ];

    // Result 폴더가 없으면 생성
    if (!file_exists('Result')) {
        mkdir('Result', 0777, true);
    }

    // 파일명 생성 (타임스탬프 기반)
    $filename = 'Result/survey_' . date('Ymd_His') . '.json';

    // JSON으로 저장
    $result = file_put_contents($filename, json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

    if ($result !== false) {
        echo json_encode([
            'success' => true,
            'message' => '설문이 성공적으로 저장되었습니다.'
        ]);
    } else {
        throw new Exception('파일 저장 실패');
    }
} catch (Exception $e) {
    // 오류 발생 시
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => '설문 저장 중 오류가 발생했습니다: ' . $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
?> 