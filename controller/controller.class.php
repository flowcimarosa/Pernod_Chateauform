<?php

include("model/model.class.php");

class Controller {

    private $unModel;

    public function __construct($host, $bdd, $user, $mdp) {
        $this->unModel = new Model($host, $bdd, $user, $mdp);
    }

    public function getLastId($sql) {
        return $this->unModel->getLastId($sql);
    }

    public function execute($sql) {
        return $this->unModel->execute($sql);
    }

    public function verify($dateSeminaire, $idSalle) {
        return $this->unModel->verify($dateSeminaire, $idSalle);
    }

    public function restriction() {
        return $this->unModel->restriction();
    }

    public function select($table, $where) {
        $this->unModel->renseigner($table);
        return $this->unModel->select($where);
    }

    public function selectThatWhere($table, $that, $where){
        $this->unModel->renseigner($table);
        return $this->unModel->selectThatWhere($that, $where);
    }

    public function selectAll($table) {
        $this->unModel->renseigner($table);
        return $this->unModel->selectAll();
    }

    public function selectAllPeriodes($idSeminaire) {
        return $this->unModel->selectAllPeriodes($idSeminaire);
    }

    public function selectAllDistinct($table) {
        $this->unModel->renseigner($table);
        return $this->unModel->selectAllDistinct();
    }

    public function selectAllCount($table) {
        $this->unModel->renseigner($table);
        return $this->unModel->selectAllCount();
    }

    public function selectWhere($table, $tab, $where) {
        $this->unModel->renseigner($table);
        return $this->unModel->selectWhere($tab, $where);
    }

    public function selectChampsWhere($table, $champs, $where) {
        $this->unModel->renseigner($table);
        return $this->unModel->selectChampsWhere($champs, $where);
    }

    public function selectAllWhere($table, $where) {
        $this->unModel->renseigner($table);
        return $this->unModel->selectAllWhere($where);
    }

    public function selectAllLimit($table, $records_per_page) {
        $this->unModel->renseigner($table);
        return $this->unModel->selectAllLimit($records_per_page);
    }

    public function selectAllWhereLimit($table, $where, $records_per_page) {
        $this->unModel->renseigner($table);
        return $this->unModel->selectAllWhereLimit($where, $records_per_page);
    }

    public function selectAllOrderLimit($table, $order, $records_per_page) {
        $this->unModel->renseigner($table);
        return $this->unModel->selectAllOrderLimit($order, $records_per_page);
    }

    public function dataview($query){
        return $this->unModel->dataview($query);
    }

    public function dataviewClient($query){
        return $this->unModel->dataviewClient($query);
    }

    public function getCreneaux($id){
        return $this->unModel->getCreneaux($id);
    }

    // public function paging($query, $records_per_page){
    //     return $this->unModel->paging($query,$records_per_page);
    // }

    public function paginglink($table, $where, $records_per_page, $consult_id){
        $this->unModel->renseigner($table);
        return $this->unModel->paginglink($where,$records_per_page, $consult_id);
    }

    public function paginglink2($table, $where, $records_per_page, $rechercheClient){
        $this->unModel->renseigner($table);
        return $this->unModel->paginglink2($where,$records_per_page, $rechercheClient);
    }

    public function paginglinkAll($table, $records_per_page){
        $this->unModel->renseigner($table);
        return $this->unModel->paginglinkAll($records_per_page);
    }

    public function selectAllWhereDistinct($tab, $table, $where) {
        $this->unModel->renseigner($table);
        return $this->unModel->selectAllWhereDistinct($tab, $where);
    }

    public function selectLastOffer($table, $limit){
        $this->unModel->renseigner($table);
        return $this->unModel->selectLastOffer($limit);
    }

    public function selectAllWithFK($table, $tab, $where) {
        $this->unModel->renseigner($table);
        return $this->unModel->selectAllWithFK($tab, $where);
    }

    public function selectChamps($table, $tab) {
        $this->unModel->renseigner($table);
        return $this->unModel->selectChamps($tab);
    }

    public function insert($table, $values) {
        $this->unModel->renseigner($table);
        $this->unModel->insert($values);
    }

    public function inscription($table, $valuesU, $valuesP) {
        $this->unModel->renseigner($table);
        $this->unModel->inscription($valuesU, $valuesP);
    }

    public function connexion($table, $tab, $where) {
        $this->unModel->renseigner($table);
        $this->unModel->connexion($tab, $where);
    }

    public function create($libelle, $logo) {
        $this->unModel->create($libelle, $logo);
    }

    public function delete($id){
        return $this->unModel->delete($id);
    }

    public function delete2($id){
        return $this->unModel->delete2($id);
    }

    public function delete3($idSeminaire, $idSalle, $debut){
        return $this->unModel->delete3($idSeminaire, $idSalle, $debut);
    }

    public function eraseSeminaire($idSeminaire)
    {
        return $this->unModel->eraseSeminaire($idSeminaire);
    }

    public function eraseDerouler($idSeminaire)
    {
        return $this->unModel->eraseDerouler($idSeminaire);
    }

    public function eraseDispo($idSeminaire)
    {
        return $this->unModel->eraseDispo($idSeminaire);
    }

    public function clean($dateSeminaire, $idSalle){
        return $this->unModel->clean($dateSeminaire, $idSalle);
    }

    public function getID($id){
        return $this->unModel->getID($id);
    }

    public function update($id, $libelle, $logo){
        return $this->unModel->update($id, $libelle, $logo);
    }

    public function update2($id, $nomSeminaire, $dateSeminaire, $affNom, $message){
        return $this->unModel->update2($id, $nomSeminaire, $dateSeminaire, $affNom, $message);
    }

    public function seminairesDuJour(){
        return $this->unModel->seminairesDuJour();
    }

    public function getInfoClient($idSeminaire){
        return $this->unModel->getInfoClient($idSeminaire);
    }

    public function search($array, $key, $value){
        return $this->unModel->search($array, $key, $value);
    }

}

?>