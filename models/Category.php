<?php
/**
 * Класс Category - модель для работы с категориями упражнениями
 */
class Category
{
  /**
 * Возвращает список упражнений
 * @param integer $id
 * @return array $ExercisesList
 */
  public static function getExercisesList($id)
  {
    $db = Db::getConnection();

    $categoryExercisesList = array();

    $result = $db->query('SELECT exercises.id, exercises.name, exercise_category.title FROM exercises INNER JOIN exercise_category ON exercises.category_id = exercise_category.id WHERE exercises.category_id = '.$id.' ORDER BY id ASC');

    $i = 0;
    while($row = $result->fetch()) {
      $categoryExercisesList[$i]['id'] = $row['id'];
      $categoryExercisesList[$i]['name'] = $row['name'];
      $categoryExercisesList[$i]['category'] = $row['title'];
      $i++;
    }

    return $categoryExercisesList;
  }
  /**
     * Возвращает порядковый номер следующего теста в категории
     * @param integer $id
     * @return integer $nextExerciseNumberByCategory
     */
  public static function showNextExerciseNumberByCategory($id)
  {
    $db = Db::getConnection();
    $result = $db->query('SELECT id FROM exercises WHERE category_id ='.$id);

    $i = 0;
    while($row = $result->fetch()) {
      ++$i;
    }
    $nextExerciseNumberByCategory = $i + 1;

    return $nextExerciseNumberByCategory;
  }
  /**
     * Возвращает имена категорий упражнений c (предвыбором)
     * @param integer $id
     * @return array
     */
  public static function getExerciseCategoryList($id)
  {
    $db = Db::getConnection();
    $result = $db->query('SELECT title,id FROM exercise_category');

    $j = 0;
    while($row = $result->fetch()) {
      $exerciseCategoryList[$j]['title'] = $row['title'];
      $exerciseCategoryList[$j]['id'] = $row['id'];
      if ($exerciseCategoryList[$j]['id'] == $id) {
        $exerciseCategoryList[$j]['selected'] = "selected";
      } else {
        $exerciseCategoryList[$j]['selected'] = "not-selected";
      }
      $j++;
    }

    return $exerciseCategoryList;
  }
}
