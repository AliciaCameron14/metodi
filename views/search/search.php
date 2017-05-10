<!--TODO Make search page prettier!  -->
<div class="col-lg-11">
  <form class="well form-search">
  		<label>Search:</label>
  		<input type="text" ng-model="search" class="input-medium search-query" placeholder="Keywords...">
  		<button type="submit" class="btn" ng-click="searchContent()">Search</button>
  </form>

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
