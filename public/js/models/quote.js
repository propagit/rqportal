/*
 *  models/quote.js
 *  This model represent quote object
 */

 angular.module('model.quote', [])

 .factory('Quote', function($http, $q, Config){

    function Quote(quoteData) {
        if (quoteData) {
            this.setData(quoteData);
        }

    };

    Quote.prototype = {
        setData: function(quoteData) {
            angular.extend(this, quoteData);
        },

        update: function() {
            var self = this;
            var deferred = $q.defer();
            $http.put(Config.BASE_URL + 'quote/ajaxUpdate/' + self.id, self)
            .success(function(response){
                deferred.resolve(response);
            })
            .error(function(error){
                deferred.reject(error);
            });
            return deferred.promise;
        }
    };

    return Quote;
 })

.factory('quotesManager', function($http, $q, Quote, Config){
    var quotesManager = {
        _pool: {},
        _retrieveInstance: function(quoteId, quoteData) {
            var instance = this._pool[quoteId];
            if (instance) {
                instance.setData(quoteData);
            } else {
                instance = new Cashgame(cashgameData);
                this._pool[cashgameId] = instance;
            }
            return instance;
        },

        _search: function(quoteId) {
            return this._pool[quoteId];
        },
        _load: function(quoteId, deferred) {
            var scope = this;
            $http.get(Config.BASE_URL + 'quote/ajaxGet/' + quoteId)
            .success(function(quoteData) {
                deferred.resolve(quoteData);
            })
            .error(function(error) {
                deferred.reject(error);
            });
        },
        /* Public Methods */
        /* Use this function in order to get a cashgame instance by it's id */
        getCashgame: function(cashgameId) {
            var deferred = $q.defer();
            var cashgame = this._search(cashgameId);

            if(cashgame) {
                deferred.resolve(cashgame);
            } else {
                this._load(cashgameId, deferred);
            }
            return deferred.promise;
        },
        /* Use this function in order to get instance of all the cashgames */
        loadAllQuotes: function() {
            var deferred = $q.defer();
            var scope = this;
            $http.get(Config.BASE_URL + 'quote/ajaxGetAll')
            .success(function(response) {
                var quotes = [];
                var quotesArray = response;
                quotesArray.forEach(function(quoteData) {
                    var quote = scope._retrieveInstance(quoteData.id, quoteData);
                    quotes.push(quote);
                });

                deferred.resolve(cashgames);
            })
            .error(function(error) {
                deferred.reject(error);
            });
            return deferred.promise;
        },
        /* This function is useful when we got somehow the quote data and we wish to store it or update the pool and get a cashgame instance in return */
        setCashgame: function(cashgameData) {
            var scope = this;
            var cashgame = this._search(cashgameData.id);
            if (cashgame) {
                cashgame.setData(cashgameData);
            } else {
                cashgame = scope._retrieveInstance(cashgameData);
            }
            return cashgame;
        }
    };
    return cashgamesManager;
})
