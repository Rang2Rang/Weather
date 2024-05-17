<?php

  	define('__CORE_TYPE__','view');
  	include $_SERVER['DOCUMENT_ROOT'].'/function/core.php';

	//Hi
	$realtime = (int)date('Hi');
	//오늘 날짜를 YYYYMMDD0600으로 선언
	$todayDate = date('Ymd').'0600';
	//어제 날짜의 YYYYMMDD1800
	$prevDate = date('Ymd', strtotime('-1 day')) . '1800';
	//오늘의 날만 가져오기
	$today = (int)date('j');


	//API적용
	$serviceKey = 'e59VqduelONmTZyJnlkEB97hFyqUWBaOULbvbsP03b74mKYUgA5EYuV6FDb96+KAA2ZZI3ltMN7ymNAkujjujA==';
	$url = 'http://apis.data.go.kr/1360000/MidFcstInfoService/getMidLandFcst';

	$url_1 = $url . '?serviceKey=' . urlencode($serviceKey) . '&numOfRows=10&pageNo=1&regId=11B00000&tmFc=' . ($realtime < 600 ? $prevDate : $todayDate); // 서울,인천,경기도
	$url_2 = $url .  '?serviceKey=' . urlencode($serviceKey) . '&numOfRows=10&pageNo=1&regId=11D10000&tmFc=' . ($realtime < 600 ? $prevDate : $todayDate); // 강원도영서
	$url_3 = $url .  '?serviceKey=' . urlencode($serviceKey) . '&numOfRows=10&pageNo=1&regId=11D20000&tmFc=' . ($realtime < 600 ? $prevDate : $todayDate); // 강원도영동
	$url_4 = $url .  '?serviceKey=' . urlencode($serviceKey) . '&numOfRows=10&pageNo=1&regId=11C20000&tmFc=' . ($realtime < 600 ? $prevDate : $todayDate); // 대전, 세종, 충남
	$url_5 = $url .  '?serviceKey=' . urlencode($serviceKey) . '&numOfRows=10&pageNo=1&regId=11C10000&tmFc=' . ($realtime < 600 ? $prevDate : $todayDate); // 충북
	$url_6 = $url .  '?serviceKey=' . urlencode($serviceKey) . '&numOfRows=10&pageNo=1&regId=11F20000&tmFc=' . ($realtime < 600 ? $prevDate : $todayDate); //  광주, 전남
	$url_7 = $url .  '?serviceKey=' . urlencode($serviceKey) . '&numOfRows=10&pageNo=1&regId=11F10000&tmFc=' . ($realtime < 600 ? $prevDate : $todayDate); //  전북
	$url_8 = $url .  '?serviceKey=' . urlencode($serviceKey) . '&numOfRows=10&pageNo=1&regId=11H10000&tmFc=' . ($realtime < 600 ? $prevDate : $todayDate); // 대구, 경북
	$url_9 = $url .  '?serviceKey=' . urlencode($serviceKey) . '&numOfRows=10&pageNo=1&regId=11H20000&tmFc=' . ($realtime < 600 ? $prevDate : $todayDate); // 부산,울산,경남
	$url_10 = $url .  '?serviceKey=' . urlencode($serviceKey) . '&numOfRows=10&pageNo=1&regId=11G00000&tmFc=' . ($realtime < 600 ? $prevDate : $todayDate); // 제주도

	//response처리과정
	for ($i = 1; $i <= 10; $i++) {
	    $url_variable = "url_$i"; 
	    $response_variable = "response_$i"; 

	    $$response_variable = file_get_contents($$url_variable); 
	}

	// xml처리과정
	for($i = 1; $i<=10; $i++){
	    $response_variable = "response_$i";
	    $xml_variable = "xml_$i";

	    $$xml_variable = new SimpleXMLElement($$response_variable);
	}


	$itemCount = count($xml_1->body->items->item);

	$result_1 = [];
	$result_2 = [];
	$result_3 = [];
	$result_4 = [];
	$result_5 = [];
	$result_6 = [];
	$result_7 = [];
	$result_8 = [];
	$result_9 = [];
	$result_10 = [];

	for ($i = 0; $i < $itemCount; $i++) {

	    // result_1 ~ result_10 처리
	    for ($j = 1; $j <= 10; $j++) {
	        $result_variable = "result_$j";
	        $xml_variable = "xml_$j";
	        
	        $item = ${$xml_variable}->body->items->item[$i];
	        for ($k = 3; $k <= 7; $k++) {
	            ${$result_variable}[(string)$item->regId][$k . '일 후 오전 강수 확률'] = (string)$item->{'rnSt' . $k . 'Am'};
	            ${$result_variable}[(string)$item->regId][$k . '일 후 오후 강수 확률'] = (string)$item->{'rnSt' . $k . 'Pm'};
	            ${$result_variable}[(string)$item->regId][$k . '일 후 오전 날씨 예보'] = (string)$item->{'wf' . $k . 'Am'};
	            ${$result_variable}[(string)$item->regId][$k . '일 후 오후 날씨 예보'] = (string)$item->{'wf' . $k . 'Pm'};
	        }
	    }
	}





    //--날짜관련 정보 불러오기--//

    $year = date("Y"); //YYYY방식
	$month = date("m");

	//현재 달의 총 날짜
	$total_day = date('t', strtotime("$year-$month-01"));


	//요일 배열
	$weekString = array("일", "월", "화", "수", "목", "금", "토");


	//전달의 마지막 주
	$lastMonthLastDay = date('Y-m-d', strtotime("$year-$month-01 -1 day"));
	$lastMonthLastDayWeekdayIndex = date('w', strtotime($lastMonthLastDay));
	$lastMonthLastDayWeekday = $weekString[$lastMonthLastDayWeekdayIndex]; 



	//그달의 첫 째 요일(시작요일)
	$firstDayofMonth = date('Y-m-d', strtotime("$year-$month-01"));
	$firstDayWeekdayIndex = date('w', strtotime($firstDayofMonth));
	$firstDayWeekday = $weekString[$firstDayWeekdayIndex];

	//이번 달 첫째 주 시작 일
	$firstWeekStartDate = date('Y-m-d', strtotime("$year-$month-01 -$firstDayWeekdayIndex day"));

	//그달의 마지막 날짜
	$daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
	$lastDayOfMonth = date('Y-m-d', strtotime("$year-$month-$daysInMonth"));

	//그달의 마지막 요일
	$lastDayWeekdayIndex = date('w', strtotime($lastDayOfMonth));
	$lastDayWeekday = $weekString[$lastDayWeekdayIndex];

	//지난 달의 마지막 주 정보 (날짜,요일) 배열 선언

	$prevMonthDays = array();
	for ($i = $lastMonthLastDayWeekdayIndex; $i >= 0; $i--) {
	    $prevMonthDays[] = date('d', strtotime("$lastMonthLastDay -$i day"));
	}

	// 다음 달 첫째 주 정보 (날짜, 요일) 배열 선언
	$nextMonthDays = array();
	$nextMonthStartDate = date('Y-m-d', strtotime("$year-$month-$daysInMonth +1 day"));
	$nextMonthStartDayWeekdayIndex = date('w', strtotime($nextMonthStartDate));

	for ($i = 0; $i <= 6 - $nextMonthStartDayWeekdayIndex; $i++) {
	    $date = date('Y-m-d', strtotime("$nextMonthStartDate +$i day"));
	    $day = date('j', strtotime($date));
	    $dayOfWeekIndex = date('w', strtotime($date));
	    $dayOfWeek = $weekString[$dayOfWeekIndex];
	    $nextMonthDays[] = array('date' => $day, 'day_of_week' => $dayOfWeek);
	}





?>

<div id="All">	
	<div id="head"><?php echo $year ?>년 <?php echo $month ?>월
	<select class="select" id="select" onchange="Change(this.value)">
		<option value="11B00000" >서울, 인천, 경기도</option>
	    <option value="11D10000" >강원도영서</option>
	    <option value="11D20000" >강원도영동</option>
	    <option value="11C20000" >대전, 세종, 충청남도</option>
		<option value="11C10000" >충청북도</option>
	    <option value="11F20000" >광주, 전라남도</option>
	    <option value="11F10000" >전라북도</option>
	    <option value="11H10000" >대구, 경상북도</option>
	    <option value="11H20000" >부산, 울산, 경상남도</option>
	    <option value="11G00000" >제주도</option>
	</select>
	<script>

		function Change(value) {
		    var url;

		    // 선택한 값에 따라 URL 설정
		    if (value === '11B00000') {
		        url = '<?php echo $url_1; ?>';
		    } else if (value === '11D10000') {
		        url = '<?php echo $url_2; ?>';
		    } else if (value === '11D20000') {
		        url = '<?php echo $url_3; ?>';
		    } else if (value === '11C20000') {
		        url = '<?php echo $url_4; ?>';
		    } else if (value === '11C10000') {
		        url = '<?php echo $url_5; ?>';
		    } else if (value === '11F20000') {
		        url = '<?php echo $url_6; ?>';
		    } else if (value === '11F10000') {
		        url = '<?php echo $url_7; ?>';
		    } else if (value === '11H10000') {
		        url = '<?php echo $url_8; ?>';
		    } else if (value === '11H20000') {
		        url = '<?php echo $url_9; ?>';
		    } else if (value === '11G00000') {
		        url = '<?php echo $url_10; ?>';
		    }

		    // URL로부터 데이터 가져오기
		    fetch(url)
		    .then(response => response.text())
		    .then(data => {
		        let parser = new DOMParser();
		        let xmlDoc = parser.parseFromString(data,"text/xml");
		        let result = [];

		        for(let i = 3; i <= 7; i++){
		            const rnStAm = xmlDoc.getElementsByTagName(`rnSt${i}Am`)[0]?.childNodes[0]?.nodeValue;
		            const rnStPm = xmlDoc.getElementsByTagName(`rnSt${i}Pm`)[0]?.childNodes[0]?.nodeValue;
		            const wfAm = xmlDoc.getElementsByTagName(`wf${i}Am`)[0]?.childNodes[0]?.nodeValue;
		            const wfPm = xmlDoc.getElementsByTagName(`wf${i}Pm`)[0]?.childNodes[0]?.nodeValue;

		            result.push({
		                [`rnSt${i}Am`]: rnStAm,
		                [`rnSt${i}Pm`]: rnStPm,
		                [`wf${i}Am`]: wfAm,
		                [`wf${i}Pm`]: wfPm
		            });
		        }

		        // 가져온 데이터로 HTML 업데이트
		        UpdateHTML(result);

		        // 선택된 값이 변경되었을 때만 selected 속성을 업데이트
		        var selectedOption = document.querySelector('.select');
		        var currentValue = selectedOption.value;
		        if (currentValue !== value) {
		            selectedOption.value = value;
		        }
		    });
		}

		function UpdateHTML(result){



			// 현재 날짜로부터 년도와 월을 가져오기
			const today = new Date();
			const year = today.getFullYear(); // 현재 년도
			const month = today.getMonth(); // 현재 월 (0부터 시작)
			const currentDay = today.getDate();
			const monthmonth = today.getMonth() + 1; //출력용 달


			const firstDayOfMonth = new Date(year, month, 1);
			const firstDayWeekdayIndex = firstDayOfMonth.getDay();

			// 해당 월의 마지막 날짜를 구하기 위해 다음 달의 0일을 구합니다.
			const lastDayOfMonth = new Date(year, month + 1, 0);
			const lastDayWeekdayIndex = lastDayOfMonth.getDay();

			// 마지막 날짜에서 일을 추출하여 해당 월의 일 수를 얻습니다.
			const daysInMonth = lastDayOfMonth.getDate();

			// 요일 배열 정의
			const weekString = ["일", "월", "화", "수", "목", "금", "토"];

			// 지난달의 마지막 날짜 구하기
			const lastDayOfLastMonth = new Date(today.getFullYear(), today.getMonth(), 0);

			// 지난달의 마지막 날짜의 문자열 형식으로 변환
			const lastMonthLastDay = lastDayOfLastMonth.toISOString().substring(0, 10);

			// 지난달의 마지막 날짜의 요일 인덱스 구하기
			const lastMonthLastDayWeekdayIndex = lastDayOfLastMonth.getDay();

			// 요일 배열에서 요일 이름 가져오기
			const lastMonthLastDayWeekday = weekString[lastMonthLastDayWeekdayIndex];




			let prevMonthDays = [];
			// 지난달 마지막 날부터 그 주의 일요일까지의 날짜들을 배열에 추가
			for (let i = lastMonthLastDayWeekdayIndex; i >= 0; i--) {
			    const day = new Date(lastDayOfLastMonth.getFullYear(), lastDayOfLastMonth.getMonth(), lastDayOfLastMonth.getDate() - i);
			    prevMonthDays.push(day.getDate()); // 날짜 (일) 부분만 추출하여 배열에 추가
			}


			html = '';

			html += '<div id="head">' + year + '년 ' + monthmonth + '월';
			html += '<select class="select" id="select" onchange="Change(this.value)">';
			html += '<option value="11B00000" >서울, 인천, 경기도</option>';
			html += '<option value="11D10000" >강원도영서</option>';
			html += '<option value="11D20000" >강원도영동</option>';
			html += '<option value="11C20000" >대전, 세종, 충청남도</option>';
			html += '<option value="11C10000" >충청북도</option>';
			html += '<option value="11F20000" >광주, 전라남도</option>';
			html += '<option value="11F10000" >전라북도</option>';
			html += '<option value="11H10000" >대구, 경상북도</option>';
			html += '<option value="11H20000" >부산, 울산, 경상남도</option>';
			html += '<option value="11G00000" >제주도</option>';
			html += '</select>';
			html += '</div>';
			html += '<div id="calendar">';
			html += '<table>';
			html += '<tr>';
			html += '<th>일</th>';
			html += '<th>월</th>';
			html += '<th>화</th>';
			html += '<th>수</th>';
			html += '<th>목</th>';
			html += '<th>금</th>';
			html += '<th>토</th>';
			html += '</tr>';
			html += '<tr>';
			//지난달의 정보를 가져와서 첫째줄 완성
			if(prevMonthDays.length < 7){
				for(let i=0;i<prevMonthDays.length;i++){
					html += `<td id='prev'>${prevMonthDays[i]}</td>`;
				}
			}

			let remain = 7 - prevMonthDays.length;


			for(let i=1; i<=remain;i++){
				html += '<td>' + i + '</td>'; 
			}
			html += '</tr>';

			//둘째줄 들어가기전 배열에 정보담기
			let values = {};

			// result 배열에서 원하는 값 추출하여 객체에 저장
			for (let i = 3; i <= 7; i++) {
			    if (result[i - 3] !== undefined) {
			        if (result[i - 3][`rnSt${i}Am`] !== undefined) {
			            values[`rnSt${i}Am`] = result[i - 3][`rnSt${i}Am`];
			        }
			        if (result[i - 3][`rnSt${i}Pm`] !== undefined) {
			            values[`rnSt${i}Pm`] = result[i - 3][`rnSt${i}Pm`];
			        }
			        if (result[i - 3][`wf${i}Am`] !== undefined) {
			            values[`wf${i}Am`] = result[i - 3][`wf${i}Am`];
			        }
			        if (result[i - 3][`wf${i}Pm`] !== undefined) {
			            values[`wf${i}Pm`] = result[i - 3][`wf${i}Pm`];
			        }
			    }
			}

			let Day = remain + 1;

			while(Day <= daysInMonth){
				let Ok = false;

				for(let i =3; i <=7 ; i++){
					if(Day === (currentDay  + i)){
						html += `<td id='xdx' >${Day}</br>오전강수확률: ${values[`rnSt${i}Am`]}</br>오후강수확률: ${values[`rnSt${i}Pm`]}</br>오전날씨예보: ${values[`wf${i}Am`]}</br>오후날씨예보: ${values[`wf${i}Pm`]}</td>`;

						Ok = true;
						break;
					}
				}
				if(!Ok){
					if(Day === currentDay){
						html += `<td style="color: red;" onclick='openDay(${Day})'>` + Day + '</td>';
					}else {
						html += '<td>' + Day + '</td>';
					}
				}
				if(((firstDayWeekdayIndex + Day - 7) % 7 == 0)){
					html += '</tr><tr>';
				}

				Day++;
			}

			const nextMonthCount = 6 - lastDayWeekdayIndex;
			if(nextMonthCount > 0){
				for(let i = 1; i<=nextMonthCount; i++){
					html += '<td id="next">' + i + '</td>';
				}
			}

			html += '</tr>';
			html += '</table>';
			html += '</div>';

		

			document.getElementById('All').innerHTML = html;
	
	}

function openDay(day) {
    console.log(day);
    let dd = document.getElementById('select');
    let selectedValue = dd.options[dd.selectedIndex].value;
    console.log(selectedValue);

    let nx, ny;

    if(selectedValue === '11B00000'){
    	console.log('서울경기인천입니다.');
    	nx = 60;
    	ny = 127;
    }else if(selectedValue === '11D10000'){
    	console.log('강원도영서입니다.');
    	nx = 73;
    	ny = 130;
    }else if(selectedValue === '11D20000'){
    	console.log('강원도영동입니다.');
    	nx = 95;
    	ny = 129; 
    }else if(selectedValue === '11C20000'){
    	console.log('대전세종충남입니다');
    	nx = 67;
    	ny = 100;
    }else if(selectedValue === '11C10000'){
    	console.log('충북입니다');
    	nx = 69;
    	ny = 107;
    }else if(selectedValue === '11F20000'){
    	console.log('광주전라입니다');
    	nx = 59;
    	ny = 74;
    }else if(selectedValue === '11F10000'){
    	console.log('전북입니다.');
    	nx = 63;
    	ny = 89;
    }else if(selectedValue === '11H10000'){
    	console.log('대구경북입니다');
    	nx = 89;
    	ny = 90;
    }else if(selectedValue === '11H20000'){
    	console.log('부울경입니다');
    	nx = 98;
    	ny = 76;
    }else if(selectedValue === '11G00000'){
    	console.log('제주도입니다');
    	nx = 52;
    	ny = 38;
    }

    const serviceKey = 'F9CBgM99fhdZl/fTgAh3DYWMPzaTBuY74hU/rZSepFMvn7QFQR0p7lTKbmb/EK9TD17v9dv08O8D5QtthijA5Q==';
    const today = new Date();
    const year = today.getFullYear();
    const month = (today.getMonth() + 1).toString().padStart(2, '0');
    const dayString = day.toString().padStart(2, '0');
    const baseDate = `${year}${month}${dayString}`;



    const url = `http://apis.data.go.kr/1360000/VilageFcstInfoService_2.0/getVilageFcst?serviceKey=${encodeURIComponent(serviceKey)}&numOfRows=10&pageNo=1&base_date=${baseDate}&base_time=0500&nx=${nx}&ny=${ny}`;

    fetch(url)
        .then(response => response.text())
        .then(str => {
            const parser = new DOMParser();
            const xmlDoc = parser.parseFromString(str, "text/xml");
            const items = xmlDoc.getElementsByTagName("item");
            let extractedData = [];

            const validCategories = {
                "POP": "강수확률",
                "PCP": "1시간 강수량",
                "REH": "습도",
                "SNO": "1시간 신적설",
                "TMP": "1시간 기온",
                "TMN": "일 최저기온",
                "TMX": "일 최고기온",
                "WAV": "파고",
                "VEC": "풍향",
                "WSD": "풍속"
            };

            const fcstUnits = {
                "POP": "%",
                "PCP": "",
                "SNO": "cm",
                "TMP": "°C",
                "TMN": "°C",
                "TMX": "°C",
                "WAV": "m",
                "VEC": "deg",
                "WSD": "m/s"
            };

            for (let i = 0; i < items.length; i++) {
                const item = items[i];
                const category = item.querySelector("category").textContent;
                const fcstValue = item.querySelector("fcstValue").textContent;

                if (category in validCategories) {
                    const translatedCategory = validCategories[category];
                    const unit = fcstUnits[category] || "";
                    const valueWithUnit = fcstValue + unit;

                    extractedData.push({
                        category: translatedCategory,
                        fcstValue: valueWithUnit
                    });
                }
            }

            console.log(extractedData);
            if (extractedData.length > 0) {
                let message = "데이터 목록:\n";
                for (let i = 0; i < extractedData.length; i++) {
                    message += `${i + 1}. ${extractedData[i].category} : ${extractedData[i].fcstValue}\n`;
                }
                alert(message);
            } else {
                alert("데이터가 없습니다.");
            }
        });
}


	</script>

	</div>
	<div id="calendar">
		<table>
	        <tr>
	            <th>일</th>
	            <th>월</th>
	            <th>화</th>
	            <th>수</th>
	            <th>목</th>
	            <th>금</th>
	            <th>토</th>
	        </tr>
	          <?php
				// 이전 달의 마지막 주와 이번 달의 첫째 주 출력
				echo "<tr>";
				// prevMonthDays의 count가 7개 미만일 때만 코드 실행
				if (count($prevMonthDays) < 7) {
				    for ($i = 0; $i < count($prevMonthDays); $i++) {
				            echo "<td id='prev' style='color: gray;'>{$prevMonthDays[$i]}</td>";
						}
				}

				$remainingDays = 7 - count($prevMonthDays);

				for($i=1; $i <= $remainingDays; $i++){
					echo "<td>$i</td>";
				}
				echo "</tr>";

				$Day = $remainingDays + 1;


				for ($i = 3; $i <= 7; $i++) {
				    ${$i . 'rnStAm'} = $result_1['11B00000']["{$i}일 후 오전 강수 확률"];
				    ${$i . 'rnStPm'} = $result_1['11B00000']["{$i}일 후 오후 강수 확률"];
				    ${$i . 'wfAm'} = $result_1['11B00000']["{$i}일 후 오전 날씨 예보"];
				    ${$i . 'wfPm'} = $result_1['11B00000']["{$i}일 후 오후 날씨 예보"];
				}




				while ($Day <= $daysInMonth) {
				    $Ok = false; // 각 날짜에 대한 출력 여부를 추적하기 위한 변수

				    for ($i = 3; $i <= 7; $i++) {
				        if ($Day === ($today + $i)) {
				            echo "<td id='xdx' >$Day</br>오전강수확률: " . ${$i . 'rnStAm'} . "</br>오후강수확률:" . ${$i . 'rnStPm'} . "</br>오전날씨예보:" . ${$i . 'wfAm'} . "</br>오후날씨예보:" . ${$i . 'wfPm'} . "</td>";
				            $Ok = true; 
				            break; 
				        }
				    }

				    if (!$Ok) { // 해당 날짜에 대한 출력이 수행되지 않았다면
				        if ($Day === $today) {
				            echo "<td style='color: red;' onclick='openDay($Day)'>$Day</td>";
				        } else {
				            echo "<td>$Day</td>";
				        }
				    }

				    if ((($firstDayWeekdayIndex + $Day - 7) % 7 == 0)) {
				        echo "</tr><tr>";
				    }
				    $Day++;
				}

				$nextMonthCount = 6 - $lastDayWeekdayIndex;
				if($nextMonthCount > 0){
					for($i = 1; $i <= $nextMonthCount; $i++){

	 					echo "<td id='next' style='color: gray;'>$i</td>";
					}
				}
				echo "</tr>";

			?>
		</table>
	</div>
</div>