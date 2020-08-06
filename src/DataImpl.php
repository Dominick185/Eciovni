<?php

declare(strict_types=1);

namespace OndrejBrejla\Eciovni;


use Nette\SmartObject;

/**
 * DataImpl - part of Eciovni plugin for Nette Framework.
 *
 * @copyright  Copyright (c) 2009 Ondřej Brejla
 * @license    New BSD License
 * @link       https://github.com/obrejla/Eciovni
 */
class DataImpl
{
	use SmartObject;

	public const DATE_FORMAT = 'd. m. Y';

	/** @var string */
	private $title;

	/** @var string */
	private $id;

	/** @var Participant */
	private $supplier;

	/** @var Participant */
	private $customer;

	/** @var string|null */
	private $variableSymbol;

	/** @var string|null */
	private $constantSymbol;

	/** @var string|null */
	private $specificSymbol;

	/** @var \DateTime */
	private $expirationDate;

	/** @var \DateTime */
	private $dateOfIssuance;

	/** @var \DateTime|null */
	private $dateOfVatRevenueRecognition;

	/** @var string|null */
	private $textBottom;

	/** @var ItemImpl[] */
	private $items;

	/** @var string|null */
	private $paymentMethod;


	/**
	 * @param ItemImpl[] $items
	 */
	public function __construct(string $id, string $title, Participant $supplier, Participant $customer, \DateTime $expirationDate, \DateTime $dateOfIssuance, array $items)
	{
		$this->setId($id);
		$this->setTitle($title);
		$this->setSupplier($supplier);
		$this->setCustomer($customer);
		$this->setExpirationDate($expirationDate);
		$this->setDateOfIssuance($dateOfIssuance);
		$this->setItems($items);
	}


	/**
	 * @param ItemImpl[] $items
	 * @return self
	 */
	public static function from(string $id, string $title, Participant $supplier, Participant $customer, \DateTime $expirationDate, \DateTime $dateOfIssuance, array $items): self
	{
		return new self($id, $title, $supplier, $customer, $expirationDate, $dateOfIssuance, $items);
	}


	public function getTitle(): string
	{
		return $this->title;
	}


	public function setTitle(string $title): void
	{
		$this->title = $title;
	}


	public function getId(): string
	{
		return $this->id;
	}


	public function setId(string $id): void
	{
		$this->id = $id;
	}


	public function getSupplier(): Participant
	{
		return $this->supplier;
	}


	public function setSupplier(Participant $supplier): void
	{
		$this->supplier = $supplier;
	}


	public function getCustomer(): Participant
	{
		return $this->customer;
	}


	public function setCustomer(Participant $customer): void
	{
		$this->customer = $customer;
	}


	public function getVariableSymbol(): string
	{
		return $this->variableSymbol ?? '0';
	}


	public function setVariableSymbol(?string $variableSymbol): self
	{
		$this->variableSymbol = $variableSymbol;

		return $this;
	}


	public function getConstantSymbol(): string
	{
		return $this->constantSymbol ?? '0';
	}


	public function setConstantSymbol(?string $constantSymbol): self
	{
		$this->constantSymbol = $constantSymbol;

		return $this;
	}


	public function getSpecificSymbol(): string
	{
		return $this->specificSymbol ?? '0';
	}


	public function setSpecificSymbol(?string $specificSymbol): self
	{
		$this->specificSymbol = $specificSymbol;

		return $this;
	}


	public function getExpirationDate(string $format = self::DATE_FORMAT): string
	{
		return $this->expirationDate->format($format);
	}


	public function setExpirationDate(\DateTime $expirationDate): void
	{
		$this->expirationDate = $expirationDate;
	}


	public function getDateOfIssuance(string $format = self::DATE_FORMAT): string
	{
		return $this->dateOfIssuance->format($format);
	}


	public function setDateOfIssuance(\DateTime $dateOfIssuance): void
	{
		$this->dateOfIssuance = $dateOfIssuance;
	}


	public function getDateOfVatRevenueRecognition(string $format = self::DATE_FORMAT): string
	{
		return $this->dateOfVatRevenueRecognition === null ? '' : $this->dateOfVatRevenueRecognition->format($format);
	}


	public function setDateOfVatRevenueRecognition(?\DateTime $dateOfVatRevenueRecognition): void
	{
		$this->dateOfVatRevenueRecognition = $dateOfVatRevenueRecognition;
	}


	public function getTextBottom(): ?string
	{
		return $this->textBottom;
	}


	public function setTextBottom(?string $textBottom): self
	{
		$this->textBottom = trim($textBottom ?? '') ?: null;

		return $this;
	}


	/**
	 * @param ItemImpl[] $items
	 * @return self
	 */
	public function addItems(array $items): self
	{
		foreach ($items as $item) {
			$this->addItem($item);
		}

		return $this;
	}


	/**
	 * @return ItemImpl[]
	 */
	public function getItems(): array
	{
		return $this->items;
	}


	/**
	 * @param ItemImpl[] $items
	 */
	public function setItems(array $items): void
	{
		$this->items = $items;
	}


	public function addItem(ItemImpl $item): self
	{
		$this->items[] = $item;

		return $this;
	}


	public function getTaxValueSum(): float
	{
		$sum = 0;
		foreach ($this->getItems() as $item) {
			$sum += $item->countTaxValue();
		}

		return $sum;
	}


	public function getUntaxedUnitValueSum(): float
	{
		$sum = 0;
		foreach ($this->getItems() as $item) {
			$sum += $item->countUntaxedUnitValue();
		}

		return $sum;
	}


	public function getFinalValueSum(): float
	{
		$sum = 0;
		foreach ($this->getItems() as $item) {
			$sum += $item->countFinalValue();
		}

		return $sum;
	}


	public function getPaymentMethod(): ?string
	{
		return $this->paymentMethod;
	}


	public function setPaymentMethod(?string $paymentMethod): void
	{
		$this->paymentMethod = $paymentMethod;
	}
}
