<?php
session_start();

// 로그인 체크
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Result 폴더의 모든 JSON 파일 읽기
$responses = [];
$resultDir = 'Result';
if (is_dir($resultDir)) {
    $files = glob($resultDir . '/*.json');
    foreach ($files as $file) {
        $content = file_get_contents($file);
        $response = json_decode($content, true);
        if ($response) {
            $responses[] = $response;
        }
    }
}

// 통계 데이터 계산
$totalResponses = count($responses);
$satisfactionSum = 0;
$satisfactionCount = 0;
$returnCount = 0;
$menuCounts = [];
$subjectiveResponses = [
    'question_8' => [],
    'question_9' => [],
    'question_11' => []
];

// 만족도 분포 (1-5점) - 각 문항별
$satisfactionDistributions = [];
for ($i = 1; $i <= 6; $i++) {
    $satisfactionDistributions[$i] = [0, 0, 0, 0, 0];
}

foreach ($responses as $response) {
    // 만족도 통계 (1-6번 문항)
    for ($i = 1; $i <= 6; $i++) {
        if (isset($response["question_$i"])) {
            $satisfaction = intval($response["question_$i"]);
            $satisfactionDistributions[$i][$satisfaction - 1]++;
            
            // 1번 문항에 대해서만 전체 평균 계산
            if ($i === 1) {
                $satisfactionSum += $satisfaction;
                $satisfactionCount++;
            }
        }
    }

    // 재방문 의향 통계
    if (isset($response['question_10']) && $response['question_10'] === '예') {
        $returnCount++;
    }

    // 메뉴 선택 통계
    if (isset($response['question_7'])) {
        $selectedMenus = is_array($response['question_7']) ? $response['question_7'] : [$response['question_7']];
        foreach ($selectedMenus as $menu) {
            if (!isset($menuCounts[$menu])) {
                $menuCounts[$menu] = 0;
            }
            $menuCounts[$menu]++;
        }
    }

    // 주관식 응답 수집
    if (isset($response['question_8']) && !empty($response['question_8'])) {
        $subjectiveResponses['question_8'][] = $response['question_8'];
    }
    if (isset($response['question_9']) && !empty($response['question_9'])) {
        $subjectiveResponses['question_9'][] = $response['question_9'];
    }
    if (isset($response['question_11']) && !empty($response['question_11'])) {
        $subjectiveResponses['question_11'][] = $response['question_11'];
    }
}

// 평균 만족도 계산 (1번 문항 기준)
$avgSatisfaction = $satisfactionCount > 0 ? $satisfactionSum / $satisfactionCount : 0;

// 재방문 의향 비율 계산
$returnRate = $totalResponses > 0 ? round(($returnCount / $totalResponses) * 100) : 0;

// 메뉴 선택 비율 데이터 준비
$menuLabels = array_keys($menuCounts);
$menuData = array_values($menuCounts);

// JSON 응답
header('Content-Type: application/json; charset=utf-8');
echo json_encode([
    'totalResponses' => $totalResponses,
    'avgSatisfaction' => $avgSatisfaction,
    'returnRate' => $returnRate,
    'returnCount' => $returnCount,
    'satisfactionDistribution1' => $satisfactionDistributions[1],
    'satisfactionDistribution2' => $satisfactionDistributions[2],
    'satisfactionDistribution3' => $satisfactionDistributions[3],
    'satisfactionDistribution4' => $satisfactionDistributions[4],
    'satisfactionDistribution5' => $satisfactionDistributions[5],
    'satisfactionDistribution6' => $satisfactionDistributions[6],
    'menuLabels' => $menuLabels,
    'menuData' => $menuData,
    'subjectiveResponses' => $subjectiveResponses
], JSON_UNESCAPED_UNICODE); 