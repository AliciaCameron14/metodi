<div class="col-lg-11 col-md-11 col-sm-11 col-xs-11" ng-if="!wordSelected">
  <div ng-if="user.userType == 0 && editMode" >
    <i class="btn fa fa-2x fa-pencil" ng-click="$event.stopPropagation(); editWordle() " aria-hidden="true"></i> edit wordle
  </div>
  <wordcloud words="words" on-click="selectWord(element.text)"></wordcloud>
</div>
<div class="col-lg-11 col-md-11 col-sm-11 col-xs-11" ng-if="wordSelected">
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
