angular.module('amazonCloudfront', [])
    
    // Upload image to the cloud
    .run(['Hooks', '$rootScope', '$http', function(Hooks, $rootScope, $http){
        
        var cloudfrontURL;
        
        // Get the cloudfront URL
        $http.get('modules/amazon-cloudfront/app/settings.php').success(function(data){
            if(data){
                // Modify image URLs
                var cloudfrontURL = function(image){
                    if(image){
                        if(image.indexOf('uploads/') === 0)
                            return data.url + image.replace(/\uploads/, '');
                        else
                            return image;
                    } else
                        return image;
                };
                
                Hooks.imageHook(cloudfrontURL);
            }
        });
        
        // Watch for image uploads, upload to S3/Cloudfront
        $rootScope.$on('fileUploaded', function(event, data){
            $http.get('modules/amazon-cloudfront/app/upload.php?file='+data.filename);
        });
    }])
    
    // Edit Settings
    .controller('amazonSettingsCtrl', ['$scope', '$http', '$rootScope', function($scope, $http, $rootScope){
        $scope.amazon = {};
        
        $http.get('modules/amazon-cloudfront/app/settings.php?auth=true').success(function(data){
            $scope.amazon.accessKey = data.accessKey;
            $scope.amazon.secretKey = data.secretKey;
            $scope.amazon.bucket = data.bucket;
            $scope.amazon.cloudfrontURL = data.cloudfrontURL;
        });
        
        // Update settings
        $scope.save = function(){
            $http.post('modules/amazon-cloudfront/app/settings.php', {
                accessKey: $scope.amazon.accessKey,
                secretKey: $scope.amazon.secretKey,
                bucket: $scope.amazon.bucket,
                cloudfrontURL: $scope.amazon.cloudfrontURL
            }).success(function(data){
                $rootScope.$broadcast('notify', {message: 'Settings Updated'});
            });
        };
    }]);