<?php

/**
 * Optimum Container Calculator
 *
 * @author fknoedt@gmail.com
 * @date 10/23/2018
 * @rotina $rotinaAqui
 */
class ContainerCalculator
{
	/**
	 * number of packages a large container can hold
	 */
	const LARGE_CONTAINER_CAPACITY = 4;

	/**
	 * number of packages a small container can hold
	 */
	const SMALL_CONTAINER_CAPACITY = 1;

	/**
	 * how much - decimal from 0 to 100 - the large containers will be prioritized over the small ones
	 * default: 100 (maximize the number of large packages)
	 * @var
	 */
	var $largeContainerPercentage = 90;

	/**
	 * if true, no free space will be left on containers (if a large one is not filled, those packages will be distributed among small ones)
	 * @var
	 */
	var $fillEveryContainer;

	/**
	 * number of small containers available for shipping
	 * @var
	 */
	var $smallContainersAvailable;

	/**
	 * number of large containers available for shipping
	 * @var
	 */
	var $largeContainersAvailable;

	/**
	 * last $packageNumber input for the calculate() method
	 * @var
	 */
	var $lastPackageNumberCalculated;

	/**
	 * last results after calculate()
	 * @see $resultModel
	 * @var array
	 */
	var $results;

	/**
	 * zero-filled $results model
	 * @var array
	 */
	static $resultModel = [
		'small_to_use' => 0,
		'large_to_use' => 0,
		'small_needed' => 0,
		'large_needed' => 0,
		'small_left' => 0,
		'large_left' => 0,
		'last_large_container_empty_slots' => 0
	];

	/**
	 * ContainerCalculator constructor.
	 * @param $smallContainersAvailable
	 * @param $largeContainersAvailable
	 * @param $largeContainerPercentage
	 */
	public function __construct($smallContainersAvailable=null, $largeContainersAvailable=null, $largeContainerPercentage=null)
	{
		if($largeContainerPercentage)
			$this->largeContainerPercentage = $largeContainerPercentage;

		if($smallContainersAvailable)
			$this->smallContainersAvailable = $smallContainersAvailable;

		if($largeContainersAvailable)
			$this->largeContainersAvailable = $largeContainersAvailable;
	}

	/**
	 * return the results from last calculate() method call
	 * @param bool $jsonFormat
	 * @return array
	 */
	public function getResults($jsonFormat=false)
	{
		return $jsonFormat ? json_encode($this->results, JSON_PRETTY_PRINT) : $this->results;
	}

	/**
	 * @param $packageNumber
	 * @param $smallContainersAvailable
	 * @param $largeContainersAvailable
	 * @throws CalculateException
	 */
	public function calculate($packageNumber, $smallContainersAvailable=null, $largeContainersAvailable=null)
	{
		if($packageNumber < 0)
			throw new CalculateException("");

		// temporary result
		$this->results = self::$resultModel;

		// temporary container usage
		$lastLargeContainer = self::LARGE_CONTAINER_CAPACITY;

		// temporary number of small and large containers to_use and needed combined
		$smallContainersUsed = 0;
		$largeContainersUsed = 0;

		// set containers availability from input
		if($smallContainersAvailable)
			$this->smallContainersAvailable = $smallContainersAvailable;

		if($largeContainersAvailable)
			$this->largeContainersAvailable = $largeContainersAvailable;

		// iteration for each container
		for($i=$packageNumber; $i > 0; $i--) {

			// always prioritize small containers
			if($this->largeContainerPercentage === 0) {
				$containerSize = 'small';
			}
			// always prioritize large containers or a large container is being filled
			elseif($this->largeContainerPercentage === 100 || $lastLargeContainer != self::LARGE_CONTAINER_CAPACITY) {
				$containerSize = 'large';
			}
			// calculate the proportion in which large x small containers are picked and choose if this container will be 'large' or 'small'
			else {

				// proportion between large and small already picked until this iteration
				$ratio = $largeContainersUsed / ($smallContainersUsed == 0 ? 1 : $smallContainersUsed);

				// if this proportion exceeds the desired ratio, balance it
				$containerSize = $this->largeContainerPercentage / 10 > $ratio ? 'large' : 'small';

			}

			// large container picked
			if($containerSize == 'large') {

				// one less space on the large container being filled
				$lastLargeContainer--;

				// no more space left on the container: get another one
				if($lastLargeContainer == 0) {

					// counter for both to_use and needed
					$largeContainersUsed++;
					$lastLargeContainer = self::LARGE_CONTAINER_CAPACITY;

					// allocate the container picked in the resulting array

					// no more large containers left: count as needed
					if($this->largeContainersAvailable == 0) {

						$this->results['large_needed']++;

					}
					// count as available to use
					else {

						$this->largeContainersAvailable--;
						$this->results['large_to_use']++;

					}

				}

			}
			// small container picked
			else {

				// counter for both to_use and needed
				$smallContainersUsed++;

				// allocate the container picked in the resulting array

				// no more small containers left: count as needed
				if($this->largeContainersAvailable == 0) {

					$this->results['small_needed']++;

				}
				// count as available to use
				else {

					$this->smallContainersAvailable--;
					$this->results['small_to_use']++;

				}

			}

		}

		// update result
		$this->results['small_left'] = $this->smallContainersAvailable;
		$this->results['large_left'] = $this->largeContainersAvailable;
		$this->results['large_small_ratio_desired'] = $this->largeContainerPercentage / 10;
		$this->results['large_small_ratio_used'] = $ratio;

		// if last large container was not filled, update the result
		if($lastLargeContainer != self::LARGE_CONTAINER_CAPACITY)
			$this->results['last_large_container_empty_slots'] = self::LARGE_CONTAINER_CAPACITY - $lastLargeContainer;

		// if no small or large container is needed, return true; else, return false
		return $this->results['small_needed'] == 0 && $this->results['large_needed'] == 0;

	}


}