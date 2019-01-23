<?php
/**
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 */

namespace ledgr\georg\Model;

use Model;
use ledgr\georg\PaymentTermInterface;
use ledgr\id\PersonalId;
use ledgr\banking\AccountInterface;
use ledgr\banking\StaticAccountBuilder;
use ledgr\amount\Amount;
use Hal\Resource;
use Hal\Link;

/**
 * Donor model
 * 
 * @author Hannes Forsgård <hannes.forsgard@fripost.org>
 */
class Donor extends Model implements PaymentTermInterface, HalSerializable
{
    use properties\Created, properties\Changed, properties\Notes;

    /**
     * @var string Name of database table
     */
    public static $_table = 'donor';

    /**
     * Paris association
     *
     * This model has many Mail
     *
     * @return Model
     */
    public function mail()
    {
        return $this->has_many('Mail');
    }

    /**
     * Paris association
     *
     * This model has many Address
     *
     * @return Model
     */
    public function address()
    {
        return $this->has_many('Address');
    }

    /**
     * Get HAL resource with object data
     *
     * @param  string $url   Url to resource
     * @param  \Hal\Link     $links Additional resource links
     * @return \Hal\Resource
     */
    public function halSerialize($url, array $links = [])
    {
        $data = $this->as_array();

        $data['mails'] = [];
        foreach ($this->mail()->find_many() as $mail) {
            $data['mails'][] = $mail->as_array();
        }

        $data['addresses'] = [];
        foreach ($this->address()->find_many() as $address) {
            $data['addresses'][] = $address->as_array();
        }

        $halResource = new Resource($url, $data);
        $halResource->setLinks($links);

        return $halResource;
    }

    /**
     * Get personal id
     *
     * @return PersonalId
     */
    public function getPersonalId()
    {
        return new PersonalId($this->id);
    }

    /**
     * Set personal id
     *
     * @param  PersonalId $id
     * @return Donor instance for chaining
     */
    public function setPersonalId(PersonalId $id)
    {
        $this->id = $id->getId();
        return $this;
    }

    /**
     * Get account
     *
     * @return AccountInterface
     */
    public function getAccount()
    {
        return StaticAccountBuilder::build($this->account);
    }

    /**
     * Get given name
     *
     * @return string
     */
    public function getGivenName()
    {
        return (string)$this->given_name;
    }

    /**
     * Set given name
     *
     * @param  string $name
     * @return Donor instance for chaining
     */
    public function setGivenName($name)
    {
        assert('is_string($name)');
        $this->given_name = $name;
        return $this;
    }

    /**
     * Get surname
     *
     * @return string
     */
    public function getSurname()
    {
        return (string)$this->surname;
    }

    /**
     * Set given name
     *
     * @param  string $name
     * @return Donor instance for chaining
     */
    public function setSurname($name)
    {
        assert('is_string($name)');
        $this->surname = $name;
        return $this;
    }

    /**
     * Set account
     *
     * @param  AccountInterface $account
     * @return Donor instance for chaining
     */
    public function setAccount(AccountInterface $account)
    {
        $this->account = (string)$account;
        return $this;
    }

    /**
     * Get amount
     *
     * @return Amount
     */
    public function getAmount()
    {
        return new Amount($this->amount, 2);
    }

    /**
     * Set amount
     *
     * @param  Amount $amount
     * @return Donor instance for chaining
     */
    public function setAmount(Amount $amount)
    {
        $amount->setPrecision(2);
        $this->amount = $amount->getString();
        return $this;
    }

    /**
     * Get current donated total
     *
     * @return Amount
     */
    public function getCurrentTotal()
    {
        return new Amount($this->current_total, 2);
    }

    /**
     * Add to current donated total
     *
     * @param  Amount $amount
     * @return Donor instance for chaining
     */
    public function addToCurrentTotal(Amount $amount)
    {
        $total = $this->getCurrentTotal();
        $total->add($amount);
        $this->current_total = $total->getString();
        return $this;
    }


    // TODO flytta alla dessa metoder till PaymentTermInterface och PaymentTermTrait...

    /**
     * Check autogiro consent is active
     *
     * @return boolean
     */
    public function isAutogiro()
    {
        return $this->isFlag(self::TERM_AG);
    }

    /**
     * Check if consent should be registered with the bank
     *
     * @return boolean
     */
    public function isRegisterWithBank()
    {
        return (
            $this->isFlag(self::TERM_BAG)
            && !$this->isFlag(self::TERM_WAITING)
        );
    }

    /**
     * Check if a response from the bank is expected
     *
     * @return boolean
     */
    public function isWaitingRegistration()
    {
        return (
            $this->isFlag(self::TERM_BAG)
            && $this->isFlag(self::TERM_WAITING)
        );
    }

    /**
     * Check if consent should be removed from the bank
     *
     * @return boolean
     */
    public function isRemoveFromBank()
    {
        return (
            $this->isFlag(self::TERM_MAG)
            && !$this->isFlag(self::TERM_WAITING)
        );
    }

    /**
     * Check if a response from the bank is expected
     *
     * @return boolean
     */
    public function isWaitingRemoval()
    {
        return (
            $this->isFlag(self::TERM_MAG)
            && $this->isFlag(self::TERM_WAITING)
        );
    }

    /**
     * Reset all registered payment terms
     *
     * @return void
     */
    public function resetPaymentTerm()
    {
        $this->payment_term = 0;
    }

    /**
     * Activate autogiro consent
     *
     * @return void
     */
    public function setAutogiro()
    {
        $this->appendFlag(self::TERM_AG);
        $this->removeFlag(self::TERM_BAG);
        $this->removeFlag(self::TERM_MAG);
        $this->removeFlag(self::TERM_WAITING);
    }

    /**
     * Consent should be registered with the bank
     *
     * @return void
     */
    public function setRegisterWithBank()
    {
        $this->appendFlag(self::TERM_BAG);
        $this->removeFlag(self::TERM_AG);
        $this->removeFlag(self::TERM_MAG);
        $this->removeFlag(self::TERM_WAITING);
    }

    /**
     * Consent should be removed from the bank
     *
     * @return void
     */
    public function setRemoveFromBank()
    {
        $this->appendFlag(self::TERM_MAG);
        $this->removeFlag(self::TERM_AG);
        $this->removeFlag(self::TERM_BAG);
        $this->removeFlag(self::TERM_WAITING);
    }

    /**
     * Flag as waiting for a response from the bank
     *
     * @return void
     */
    public function setWaiting()
    {
        $this->appendFlag(self::TERM_WAITING);
    }

    /**
     * Check if PaymentTermInterface flag is set
     *
     * @param  int  $flag
     * @return bool
     */
    private function isFlag($flag)
    {
        return ($flag & $this->payment_term) == $flag;
    }

    /**
     * Append PaymentTermInterface flag
     *
     * @param  int  $flag
     * @return void
     */
    private function appendFlag($flag)
    {
        $this->payment_term |= $flag;
    }

    /**
     * Remove PaymentTermInterface flag
     *
     * @param  int  $flag
     * @return void
     */
    private function removeFlag($flag)
    {
        $this->payment_term &= ~$flag;
    }
}
