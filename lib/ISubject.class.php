<?php
interface ISubject{
	public function registerObserver($observer);
	public function removeObserver($observer);
	public function notifyObservers();
}
