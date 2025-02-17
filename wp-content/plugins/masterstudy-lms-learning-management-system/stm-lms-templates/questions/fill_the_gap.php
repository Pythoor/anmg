<?php
/**
 * @var string $type
 * @var array $answers
 * @var string $question
 * @var string $question_explanation
 * @var string $question_hint
 */
$question_id = get_the_ID();

stm_lms_register_style('fill_the_gap');

if (!empty($answers[0]) and !empty($answers[0]['text'])):

    $text = $answers[0]['text'];
    $matches = stm_lms_get_string_between($text, '|', '|');
    $inputs = array();
    if(!empty($matches)) {
        $matches = array_map(function($answer) {return "|{$answer}|";}, $matches);
        foreach($matches as $match_index => $match) {
            $width = 'width: ' . (strlen($match) * 8 + 16) . 'px';
            $name = "{$question_id}[{$match_index}]";
            $inputs[$match_index] = "<input type='text' name='{$name}' style='{$width}' />";
        }
    }

    $text = str_replace($matches, $inputs, $text);


    ?>

    <div class="stm_lms_question_item_fill_the_gap">
        <?php echo $text; ?>
    </div>

<?php endif; ?>