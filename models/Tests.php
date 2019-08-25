<?php
/**
 * Класс Tests - модель для работы с заданиями
 */
class Tests
{
  /**
     * Возвращает атрибуты заданий по id упражнения
     * @param integer $id
     * @return array
     */
  public static function getTestsListById($id)
  {
    $id = intval($id);
    if ($id) {
      $db = Db::getConnection();
      $result = $db->query('SELECT id,title,media_link,text_block,extra FROM tests WHERE exercise_id='.$id.' ORDER BY id DESC');

      $i = 0;
      while($row = $result->fetch()) {
        $testsList[$i]['id'] = $row['id'];
        $testsList[$i]['title'] = $row['title'];
        $testsList[$i]['meta'] = preg_replace("~https://www.youtube.com/watch\?v=~",'',$row['media_link']);
        $testsList[$i]['text'] = $row['text_block'];
        $testsList[$i]['extra'] = $row['extra'];
        $i++;
      }
      if (isset($testsList)) {
        return $testsList;
      }
    }
  }
  /**
     * Возвращает порядковый номер следующего задания
     * @param integer $id
     * @return integer $nextTestNumber
     */
  public static function showNextTestNumber($id)
  {
    $db = Db::getConnection();
    $result = $db->query('SELECT id FROM  tests WHERE exercise_id ='.$id);

    $i = 0;
    while($row = $result->fetch()) {
      ++$i;
    }
    $nextTestNumber = $i + 1;

    return $nextTestNumber;
  }
  /**
   * Создает новое задание
   * @param array $newTestAttributesArray
   * @param integer $id
   */
    public static function createTest($newTestAttributesArray, $id)
    {
      //$var = $newTestAttributesArray['title'];
      if ($newTestAttributesArray) {
      $db = Db::getConnection();

      $result = $db->query('INSERT INTO tests (exercise_id, test_type_id, title, media_link, text_block, extra, answer)
                            VALUES ('.$id.', 1, "'.$newTestAttributesArray["title"].'", "'.$newTestAttributesArray["media"].'", "'.$newTestAttributesArray["text"].'", "'.$newTestAttributesArray["extra"].'", "'.$newTestAttributesArray["answer"].'")');
      }
    }
  /**
     * Возвращает кол-во правильных ответов задания
     * @param integer $id
     * @param array $answersArray
     * @return integer $rightAnswersCount
     */
  public static function showRightAnswersCount($id, $answersArray)
  {
     if ($answersArray) {
     $db = Db::getConnection();
     $result = $db->query('SELECT id,answer FROM tests WHERE exercise_id='.$id.' ORDER BY id DESC');

      while($row = $result->fetch()) {
        $exerciseRightAnswers[$row['id']] = $row['answer'];
      }
      $exerciseResults = array_intersect_assoc($answersArray, $exerciseRightAnswers);
      $rightAnswersCount = count($exerciseResults);

      return $rightAnswersCount;
     }
  }
  /**
     * Возвращает общее кол-во ответов задания
     * @param integer $id
     * @return integer $answersCount
     */
  public static function showAnswersCount($id)
  {
    if ($id) {
      $db = Db::getConnection();
      $result = $db->query('SELECT id FROM tests WHERE exercise_id='.$id);

      $i = 0;
      while($row = $result->fetch()) {
        ++$i;
      }

      $answersCount = $i;

      return $answersCount;
    }
  }
  /**
     * Возвращает массив с правильными ответами
     * @param integer $id
     * @param array $answersArray
     * @return integer $answersCount
     */
  public static function showRightAnswersArray($id, $answersArray)
  {
    if ($id) {
      $db = Db::getConnection();
      $result = $db->query('SELECT answer, id FROM tests WHERE exercise_id='.$id.' ORDER BY id DESC');

      $i = 0;
      while($row = $result->fetch()) {
        $rightAnswersArray[$i]["text"] = $row['answer'];
        if ($answersArray[$row['id']] == $row['answer']) {
          $rightAnswersArray[$i]["status"] = "right";
        } else {
          $rightAnswersArray[$i]["status"] = "false";
        }
        $i++;
      }
      return $rightAnswersArray;
    }
  }
  /**
     * Удаляет задание
     * @param integer $testId
     */
  public static function deleteTest($testId)
  {
    if ($testId) {
    $db = Db::getConnection();

    $result = $db->query('DELETE FROM tests WHERE id ='.$testId);
    }
    return true;
  }
  /**
     * Вывод теста для редактирования
     * @param integer $testId
     * @return array $editTestViewArray
     */
  public static function editTestView($testId)
  {
    if ($testId) {
      $db = Db::getConnection();

      $result = $db->query('SELECT title,media_link,text_block,extra,answer FROM tests WHERE id='.$testId);

      $row = $result->fetch();

      $editTestViewArray['testId'] = $testId;
      $editTestViewArray['title'] = $row['title'];
      $editTestViewArray['meta'] = $row['media_link'];
      $editTestViewArray['text'] = $row['text_block'];
      $editTestViewArray['extra'] = $row['extra'];
      $editTestViewArray['answer'] = $row['answer'];

      return $editTestViewArray;
    }

  }
  /**
     * Сохранение отредактированного теста
     * @param integer $testId
     * @param integer $testEditSaveArray
     */
  public static function editTestSave($testId, $testEditSaveArray)
  {
      $db = Db::getConnection();

      $result = $db->query('UPDATE tests SET title = "'.$testEditSaveArray["title"].'", media_link = "'.$testEditSaveArray["media"].'", text_block = "'.$testEditSaveArray["text"].'", extra = "'.$testEditSaveArray["extra"].'", answer = "'.$testEditSaveArray["answer"].'" WHERE id='.$testId);
  }
}
