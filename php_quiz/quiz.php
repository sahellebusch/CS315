<?php
/*
 * Author  => Sean Hellebusch
 * Version  => 1.0.0
 * 
 * This program presents a quiz to the user and reports
 * results upon completion.
 */  

error_reporting(E_ALL);
ini_set('display_errors', '1');

define('FACTS_FILENAME', '/txt/facts.txt');
define('WORLD_WONDER_QUESTION', '/txt/world_wonder.txt');
define('COASTLINE_QUESTION', '/txt/coastline.txt');
define('FLAG_QUESTION', '/txt/flag.txt');
define('ANSWERS_FILENAME', '/txt/answers.txt');
define('COUNTRIES_FILENAME', '/txt/countries.txt');
define('UNANSWERED', 'unanswered');

// get two questions from facts.txt file
function get_2_facts_questions()
{
    $facts_questions = array();
    $facts           = file(FACTS_FILENAME, FILE_IGNORE_NEW_LINES);
    shuffle($facts);
    for ($i = 0; $i < 2; $i++) {
        list($question, $answer) = explode("\t", $facts[$i]);
        $fact_questions[$i] = $question;
    }
    return $fact_questions;
}

// get the coastline question
function get_coastline_question()
{
    $file = file(COASTLINE_QUESTION, FILE_IGNORE_NEW_LINES);
    list($question, $answer) = explode("\t", $file[0]);
    return $question;
}

// get the question about the world wonder in Peru
function get_world_wonder_questions()
{
    $file = file(WORLD_WONDER_QUESTION, FILE_IGNORE_NEW_LINES);
    list($question, $answer) = explode("\t", $file[0]);
    return $question;
}

// get the question about the flag of Argentina
function get_flag_question()
{
    $file = file(FLAG_QUESTION, FILE_IGNORE_NEW_LINES);
    list($question, $answer) = explode("\t", $file[0]);
    return $question;
}

// gather all questions and put them into a single array
function get_questions()
{
    $quiz_questions   = array();
    $fact_questions   = get_2_facts_questions();
    for ($i = 0; $i < sizeof($fact_questions); $i++)
        array_push($quiz_questions, $fact_questions[$i]);
    array_push($quiz_questions, get_world_wonder_questions());
    array_push($quiz_questions, get_flag_question());
    array_push($quiz_questions, get_coastline_question());
    
    return ($quiz_questions);
}

// gets the correct answer and a few others to present to 
// the user for multiple choice questions.
function get_mult_choice_answers($question)
{
    $answers        = array();
    $answers_file   = file(ANSWERS_FILENAME, FILE_IGNORE_NEW_LINES);
    $countries_file = file(COUNTRIES_FILENAME, FILE_IGNORE_NEW_LINES);
    // first, get the correct answer
    for ($i = 0; $i < sizeof($answers_file); $i++) {
        list($curr_question, $answer) = explode("\t", $answers_file[$i]);
        if ($question == $curr_question) {
            array_push($answers, $answer);
            break;
        }
    }
    // get three more random, incorrect answers 
    shuffle($countries_file);
    $num_random_answers = 3;
    $index              = 0;
    while ($num_random_answers > 0) {
        $wrong_answer = $countries_file[$index];
        if ($wrong_answer != $answers[0]) {
            array_push($answers, $wrong_answer);
            $num_random_answers--;
        }
        $index++;
    }
    return $answers;
}

// get answer for coastline question
function get_coastline_answers()
{
  $correct_answers = 2;
    $answers        = array();
    $coastline      = file(COASTLINE_QUESTION, FILE_IGNORE_NEW_LINES);
    $countries      = file(COUNTRIES_FILENAME, FILE_IGNORE_NEW_LINES);
    // there are two correct answers, get them
    for ($i = 0; $i < $correct_answers; $i++) {
        list($question, $answer) = explode("\t", $coastline[$i]);
        array_push($answers, $answer);
    }
    // get three random, incorrect answers
    shuffle($countries);
    $num_random_answers = 3;
    $index              = 0;
    while ($num_random_answers > 0) {
        $wrong_answer = $countries[$index];
        if ($wrong_answer != $answers[0]) {
            array_push($answers, $wrong_answer);
            $num_random_answers--;
        }
        $index++;
    }
    return $answers;
}

// get all questions that were give to the user.
// these were put into the POST array
function get_questions_from_post()
{
    $questions = array();
    array_push($questions, $_POST['question1']);
    array_push($questions, $_POST['question2']);
    array_push($questions, $_POST['question3']);
    array_push($questions, $_POST['question4']);
    array_push($questions, $_POST['question5']);
    return $questions;
}

// get user answers from POST array.  
function get_user_answers()
{
    $answers = array();
  // check to seeif they were set first.  
  // if not, put UNANSWERED into as a place holder.
    if (isset($_POST['country_q1'])) {
        array_push($answers, $_POST['country_q1']);
    } else
        array_push($answers, UNANSWERED);
    if (isset($_POST['country_q2'])) {
        array_push($answers, $_POST['country_q2']);
    } else
        array_push($answers, UNANSWERED);
    if (isset($_POST['world_wonder_q'])) {
        array_push($answers, $_POST['world_wonder_q']);
    } else
        array_push($answers, UNANSWERED);
    if (isset($_POST['flag_q'])) {
        array_push($answers, $_POST['flag_q']);
    } else
        array_push($answers, UNANSWERED);
    if (isset($_POST['coastline_q'])) {
        array_push($answers, $_POST['coastline_q']);
    } else
        array_push($answers, UNANSWERED);
    
    for ($i = 0; $i < sizeof($answers); $i++) {
        $answers[$i] = strtolower($answers[$i]);
        $answers[$i] = trim($answers[$i], "\.\!\?\,");
    }
    // remove excess characters
    for ($i = 0; $i < sizeof($answers); $i++) {
        $answers[$i] = trim($answers[$i]);
    }
    return $answers;
}

// get answers to all the questions.
function get_answers($questions)
{
    $quiz_answers = array();
    $answers      = file(ANSWERS_FILENAME, FILE_IGNORE_NEW_LINES);
    foreach ($questions as $q):
        foreach ($answers as $a):
            list($question, $answer) = explode("\t", $a);
            if ($q == $question):
                array_push($quiz_answers, $answer);
            endif;
        endforeach;
    endforeach;
    
    return $quiz_answers;
}

// check answers, return them in answers array
function get_results( $user_answers, $quiz_answers )
{
    $results = array();
  $correct = 0;
  // the first 4 questions only have one answer, check them
  for( $i = 0; $i < 4; $i ++ ) {
    if( strcasecmp( $user_answers[$i], $quiz_answers[$i] ) == 0 )
      array_push( $results, 1 );
    else array_push( $results, 0 ); 
  }
  // the last question has 2 possible answers, check them
  if( strcasecmp( $user_answers[4], $quiz_answers[4] ) == 0
    or strcasecmp( $user_answers[4], $quiz_answers[5] ) == 0)
      array_push( $results, 1 );
  else array_push( $results, 0 );
    
  return $results;
}
?>
<!DOCTYPE html>
<html>

  <head>
    <meta charset="utf-8" />
    <meta name="author" content="Sean Hellebusch" />
    <link rel="stylesheet" href="quiz.css">
    <title>South America Quiz</title>
  </head>
  
  <body>
    <?php
      //if one of the questions has been answered, then report results.
      if (isset($_POST['country_q1']) 
        or isset($_POST['country_q2']) 
        or isset($_POST['coastline']) 
        or isset($_POST['world_wonder']) 
        or isset($_POST['flag'])):
        $name         = $_POST['name'];
        $questions    = get_questions_from_post();
        $user_answers = get_user_answers();
        $quiz_answers = get_answers($questions);
        $results      = get_results( $user_answers, $quiz_answers );
        $correct      = count(array_keys($results, 1));

    ?>
      <form class="quiz-form" method="post" action="quiz.php">
        <div id="head">
          <h1><?= $name ?>, you scored <?= $correct ?> / 5</h1>
        </div>
        <ol>
    <?php
      for( $i = 0; $i < sizeof( $questions ); $i++ ){
        if( $results[$i] == 1 ) {
    ?>
          <li><?= $questions[$i] ?> CORRECT</li>
    <?php
        }
        else {
    ?>
          <li><?= $questions[$i] ?> INCORRECT
            <ul>
    <?php
          if( $i < 4 ) {
    ?>
              <li>The answer is <?= $quiz_answers[$i] ?></li>
            </ul>
          </li>
    <?php
        } else {

    ?>
              <li>The answer is <?= $quiz_answers[$i] ?> or 
                <?= $quiz_answers[$i + 1] ?> </li>
            </ul>
          </li>
    <?php
        }
    ?>
    <?php
        }
      }
    ?>
        </ol>
      </form>
      <?php
      endif;
      // otherwise, get info and begin quiz
      if (isset($_POST['firstname'])):
        $name  = $_POST['firstname'] . ' ' . $_POST['lastname'];
        $email = $_POST['email'];
        $quiz_questions = get_questions();
    ?>
    <form method="post" action="quiz.php" class="quiz-form" >
      <div id="head">
        <h1>Alright, <?= $name ?>.</h1>
        <p>Let's see what you know about South America!</p>
      </div>
      <?php
        $answers = get_mult_choice_answers($quiz_questions[0]);
        shuffle($answers);
      ?>

        <p>
          <label for="country_q1"><?= $quiz_questions[0] ?></label>
          <select name="country_q1" id="country_q1">
            <option value="<?= $answers[0] ?>"><?= $answers[0] ?></option>
            <option value="<?= $answers[1] ?>"><?= $answers[1] ?></option>
            <option value="<?= $answers[2] ?>"><?= $answers[2] ?></option>
            <option value="<?= $answers[3] ?>"><?= $answers[3] ?></option>
          </select>
        </p>  

      <?php
        $answers = get_mult_choice_answers($quiz_questions[1]);
        shuffle($answers);
      ?>

      <p>
        <label for="country_q2"><?= $quiz_questions[1] ?></label>
        <ul>
          <li><input type="radio" name="country_q2" id="country_q2" 
                     value="<?= $answers[0] ?>" /><?= $answers[0] ?></li>
          <li><input type="radio" name="country_q2"  
                     value="<?= $answers[1] ?>" /><?= $answers[1] ?></li>
          <li><input type="radio" name="country_q2"  
                     value="<?= $answers[2] ?>" /><?= $answers[2] ?></li>
          <li><input type="radio" name="country_q2"  
                     value="<?= $answers[3] ?>" /><?= $answers[3] ?></li>
        </ul>
      </p>

      <p>
        <label for="world_wonder_q"><?= $quiz_questions[2] ?></label>
        <input type="text" id="world_wonder_q" name="world_wonder_q" />
      </p>

      <p>
        <label for="flag_q"><?= $quiz_questions[3] ?></label>
        <input type="text" id="flag_q" name="flag_q" />
      </p>

      <p>
        <img src="argentina_flag.jpg" 
             alt="blue and white striped flag with sun in middle" >      
      </p>


      <?php
        $answers = get_coastline_answers();
        shuffle($answers);
      ?>
      <p>
        <label for="coastline_q"><?= $quiz_questions[4] ?></label>
        <ul>
          <li><input type="checkbox" name="coastline_q" 
                     id="coastline_q" 
                     value="<?= $answers[0] ?>" ><?= $answers[0] ?></li>
          <li><input type="checkbox" name="coastline_q" 
                     value="<?= $answers[1] ?>" ><?= $answers[1] ?></li>
          <li><input type="checkbox" name="coastline_q" 
                     value="<?= $answers[2] ?>" ><?= $answers[2] ?></li>
          <li><input type="checkbox" name="coastline_q" 
                     value="<?= $answers[3] ?>" ><?= $answers[3] ?></li>
          <li><input type="checkbox" name="coastline_q" 
                     value="<?= $answers[4] ?>" ><?= $answers[4] ?></li>
        </ul>
      </p>

      <input type="hidden" name="name" value="<?= $name ?>" />
      <input type="hidden" name="email" value="<?= $email ?>" />
      <input type="hidden" name="question1" 
             value="<?= $quiz_questions[0] ?>" />
      <input type="hidden" name="question2" 
             value="<?= $quiz_questions[1] ?>" />
      <input type="hidden" name="question3" 
             value="<?= $quiz_questions[2] ?>" />
      <input type="hidden" name="question4" 
             value="<?= $quiz_questions[3] ?>" />
      <input type="hidden" name="question5" 
             value="<?= $quiz_questions[4] ?>" />
      <button id="submit" class="button" 
              type="submit">Submit Answers</button>
    </form>
    <?php
      endif;
    ?>
  </body>

</html>
