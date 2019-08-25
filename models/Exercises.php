<?php
/**
 * Класс Exercises - модель для работы с упражнениями
 */
class Exercises
{
  /**
 * Возвращает список заданий
 * @return array $ExercisesList
 */
  public static function getExercisesList()
  {
    $db = Db::getConnection();

    $ExercisesList = array();

    $result = $db->query('SELECT exercises.id, exercises.name, exercise_category.title FROM exercises INNER JOIN exercise_category ON exercises.category_id = exercise_category.id ORDER BY id ASC');

    $i = 0;
    while($row = $result->fetch()) {
      $ExercisesList[$i]['id'] = $row['id'];
      $ExercisesList[$i]['name'] = $row['name'];
      $ExercisesList[$i]['category'] = $row['title'];
      $i++;
    }

    return $ExercisesList;
  }
  /**
     * Возвращает название задания по id
     * @param integer $id
     * @return string
     */
  public static function getExerciseTitle($id)
  {
    $id = intval($id);
    if ($id) {
      $db = Db::getConnection();

      $result = $db->query('SELECT exercises.name, exercise_category.title FROM exercises INNER JOIN exercise_category ON exercises.category_id = exercise_category.id WHERE exercises.id = '.$id);
      $result->setFetchMode(PDO::FETCH_ASSOC);
      $row = $result->fetch();
      $ExercisesTitle['name'] = $row['name'];
      $ExercisesTitle['category'] = $row['title'];
    }
    return $ExercisesTitle;
  }
  /**
     * Создает новое упражнение
     * @param string $newExerciseName
     * @param string $newExerciseCategory
     */
  public static function createExercise($newExerciseName, $newExerciseCategory)
  {
    if ($newExerciseName && $newExerciseCategory) {
    $db = Db::getConnection();

    $result = $db->query('SELECT id FROM exercise_category WHERE title LIKE "'.$newExerciseCategory.'"');
    $row = $result->fetch();
    $exerciseCategoryId = $row['id'];

    $resultExerciseCategory = $db->query('INSERT INTO exercises (category_id, name) VALUES ("'.$exerciseCategoryId.'", "'.$newExerciseName.'")');

    return $exerciseCategoryId;
    }
  }
  /**
     * Возвращает порядковый номер следующего теста
     * @return integer $nextExerciseNumber
     */
  public static function showNextExerciseNumber()
  {
    $db = Db::getConnection();
    $result = $db->query('SELECT id FROM exercises');

    $i = 0;
    while($row = $result->fetch()) {
      ++$i;
    }
    $nextExerciseNumber = $i + 1;

    return $nextExerciseNumber;
  }
  /**
     * Возвращает имена категорий упражнений
     * @return array
     */
  public static function getExerciseCategoryList()
  {
    $db = Db::getConnection();
    $result = $db->query('SELECT title FROM exercise_category');

    $i = 0;
    while($row = $result->fetch()) {
      $exerciseCategoryList[$i] = $row['title'];
      $i++;
    }

    return $exerciseCategoryList;
  }
  /**
     * Удаляет упражнение
     * @param integer $exerciseId
     */
  public static function deleteExercise($exerciseId)
  {
    if ($exerciseId) {
    $db = Db::getConnection();

    $result = $db->query('DELETE FROM exercises WHERE id ='.$exerciseId);
    $result = $db->query('DELETE FROM tests WHERE exercise_id ='.$exerciseId);
    }
  }
}
