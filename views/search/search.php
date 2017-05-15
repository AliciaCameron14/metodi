<div class="well col-lg-11 col-md-11 col-sm-11 col-xs-11">
  <form class=" form-search">
  		<label class="col-lg-1 col-md-2 col-sm-2 col-xs-2 lead">Search:</label>
      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
        <input type="text" ng-model="search" class="form-control input-medium search-query" placeholder="Keywords...">
      </div>
      <div class="col-lg-2 col-md-4 col-sm-4 col-xs-4">
  		<button type="submit" class="btn btn-success form-control" ng-click="searchContent()">Search</button>
    </div>
  </form>
<div class="row">

</div>
  <div ng-if="requirementResults.length">
    <h3>Requirements</h3>
    <div ng-repeat="req in requirementResults">
    <a href="" ng-click="selectResult(2, req)"> <b>{{req.requirementId }}</b> {{req.description}}</a>
    </div>
  </div>

<div ng-if="functionalityResults.length">
  <h3>Functionalities</h3>
  <div ng-repeat="func in functionalityResults">
    <a href=""  ng-click="selectResult(3, func)"><b>{{func.requirementId  + " " +  func.functionalityId }}</b> {{func.description}}</a>
  </div>
</div>

<div ng-if="exampleResults.length">
  <h3>Examples</h3>
  <div ng-repeat="ex in exampleResults">
  <a href=""  ng-click="selectResult(4, ex)"><b> {{ex.requirementId + " " + ex.functionalityId + " " + ex.exampleId }}</b>  {{ex.title}}</a>
  </div>
</div>
</div>
