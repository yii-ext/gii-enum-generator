<?php
/**
 * This is the template for generating the model class of a specified table.
 * - $this: the ModelCode object
 * - $className: the enumerable class name
 * @author Gladchenko Oleg
 */
?>
<?php echo "<?php\n"; ?>

/**
 * This is the enumerable class.
 * <?php if($this->author) echo '@author ' . $this->author; ?>
 
 */
class <?php echo $className; ?> extends <?php echo $this->baseClass."\n"; ?>
{

    <?php foreach ($this->preparedItems as $key => $value) : ?>

    /**
     * @var <?php echo gettype($value['value']); ?> <?php echo $key; ?>
     
     */
     const <?php echo $key; ?> = <?php echo !is_numeric($value['value']) && gettype($value['value']) == 'string' ? "'" . strtolower($value['value']) . "'" : $value['value']; ?>;
    <?php endforeach; ?>

	/**
     * @return array of key => values
     * <?php if($this->author) echo '@author ' . $this->author; ?>
     
     */
	public static function listData()
	{
		return  array(
        <?php foreach ($this->preparedItems as $key => $value) : ?>
            self::<?php echo $key; ?> => <?php echo gettype($value['text']) == 'string' ? "'" . $value['text'] . "'" : $value['text']; ?>,
        <?php endforeach; ?>
        );
	}
    
    /**
     * @var key
     * @return string label
     * <?php if($this->author) echo '@author ' . $this->author; ?>
     
     */
    public static function getLabel($key)
    {
        $list = self::listData();
        if (isset($list[$key])) {
            return $list[$key];
        }
    }
}
