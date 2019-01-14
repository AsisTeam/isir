# ISIR Insolvency Registry

This API wrapper allows you to check whether there is some insolvency record for given person/company in czech insolvency registry. 
The original documentation that contains some additional details is - [here - Popis_WS_2_v1_7.pdf](https://github.com/AsisTeam/isir/blob/master/.docs/Popis_WS_2_v1_7.pdf)

## Client

In order to use this api wrapper you need to instantiate a `AsisTeam\ISIR\Client\InsolvencyCheckerClient`.
You can do it manually or use it's `InsolvencyCheckerClientFactory` factory class.
There is an optional parameter `Options $opts`, if you pass it, given options will be used for every request made by client afterwards.
Options may be overwritten on request level by another Options object passed to particular call.

The simplest client creation is following
```php
$client = (new InsolvencyCheckerClientFactory())->create();
``` 

Client offers you to check if company/person has some insolvency attached or gte insolvency detail by passing it's code and vintage.

### List of available methods
- checkCompanyById($companyIco): Insolvency
- checkCompanyByName($companyName): Insolvency
- checkPersonById($personalId): Insolvency
- checkPersonByName($firstname, $lastname): Insolvency[]
- checkPersonByNameAndBirth($lastname, $birthday): Insolvency[]
- checkProceeding($code, $vintage): Insolvency

##### Options param

Every method above has optional parameter `boolean $activeOnly`. When set to true, only currently active insolvencies will be queried.
Otherwise currently active and/or historical ones will be queried.

##### ActiveOnly param

`CheckPersonByName`, `checkPersonByNameAndBirth` and `checkCompanyByName` methods have last optional parameter `Options $opts`.
By passing this object to those methods you can modify, how the remote API will search in its registries.
All `Options` parameters are optional. The Options object passed to method will overwrite the Options object used while creating the client.

- maxResultCount: max number of insolvencies to be returned by API for single query
- maxResultRelevancy: set the "deepness" of the searching
- exactNameMatch: only exact matches will be present in results
- useDiacritics: whether to use or not diacritics for searching in registries

#### Insolvency result object

If some insolvency is found a single `AsisTeam\ISIR\Entity\Insolvency` object or array of `Insolvency` objects is being returned.
When there is no record for given params (combination of names, ico, personalId, etc) the `NoRecordFoundException` exception is being thrown.

#### Exceptions

In case of any invalid given data, server error or other error `RequestException` or `ResponseException` (or their child exceptions) are thrown.


### Usage

```php
$client = (new InsolvencyCheckerClientFactory())->create();

// check company insolvency
try {
    $ins = $client->checkCompanyById('27680339', true); // true -> only active insolvencies, false -> query active OR historical insolvencies
    echo $ins->headerToString(); // print insolvency legal header
    echo $ins->addressToString(); // print address
    echo $ins->subjectToString(); // print company/person details
} catch (NoRecordFoundException $e) {
    // company does not have any insolvency attached
}

// check person insolvency by his PID
try {
    $ins = $client->checkpersonById('880712/3244'); // may be used without slash too> "8807123244"
    echo $ins->subjectToString(); // print company/person details
} catch (NoRecordFoundException $e) {
    // company does not have any insolvency attached
}

// find limited number of people with insolvency
try {
    $opts = new Options(5);
    $ins = $client->checkPersonByName('Tomáš', 'Sedláček');
    echo count($ins); // how many records was found (will be less or equal 5)
} catch (NoRecordFoundException $e) {
    // any person does not match given name and surname
}

// find insolvency detail
try {
    $ins = $client->checkProceeding(123456, 2018);
    // print insolvency legal header (eg. "Krajský soud v Brně, 40 INS 11095 / 2018")
    echo $ins->headerToString(); 
} catch (NoRecordFoundException $e) {
    //no insolvency with such code and vintage
}
```

### Insolvency entity

Client public params return single `Insolvency` entity or list of these entities as described above in the "List of available methods".
`Insolvency` entity has a getter function for fields that remote API provides (legal fields, address fields etc).
It can be annoying to compose some readable output from these getters, so there are several methods available in `Insolvency` entity:
- headerToString(): string [example output: "Krajský soud v Brně, 40 INS 11095 / 2018"]
- addressToString(): string [example output: "SÍDLO FY, J. Fučíka 308, Holasice 664 61"]
- subjectToString(): string [example output: "ID: 27680339, SCF SERVIS, s.r.o.", "ID: 661227/1234, Josef Bolvan"]

These can be used for your simple outputs.

## Nette

You can setup package as Nette compiler extension using neon config
Extension will create all client factories as services

### Usage

```neon
extensions:
    isir: AsisTeam\ISIR\DI\ISIRExtension

isir:
    # max records count to be returned on *ByName calls
    max_result_count: 200
    
    # limiting the results by relevancy (higher relevancies will be ommited), please see official doc
    max_result_relevancy: 7
    
    # querying by name must find exact name match 
    exact_name_match: false
    
    # use diacritics or no for searching
    use_diacritics: true
```
