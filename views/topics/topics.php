<div class="col-lg-11" ng-if="!wordSelected">
  <wordcloud words="words" on-click="selectWord(element)"></wordcloud>
</div>
<div class="col-lg-11"  ng-if="wordSelected">
  <div ng-if="requirementLinks.length">
    <h3>Requirements</h3>
    <div ng-repeat="req in requirementLinks">
    <a href="" ng-click="selectResult(2, req)"> <b>{{req.requirementId }}</b> {{req.description}}</a>
    </div>
  </div>

<div ng-if="functionalityLinks.length">
  <h3>Functionalities</h3>
  <div ng-repeat="func in functionalityLinks">
    <a href=""  ng-click="selectResult(3, func)"><b>{{func.requirementId  + " " +  func.functionalityId }}</b> {{func.description}}</a>
  </div>
</div>

<div ng-if="exampleLinks.length">
  <h3>Examples</h3>
  <div ng-repeat="ex in exampleLinks">
  <a href=""  ng-click="selectResult(4, ex)"><b>{{ex.requirementId + " " + ex.functionalityId + " " + ex.exampleId }}</b>  {{ex.title}}</a>
  </div>
</div>
</div>
