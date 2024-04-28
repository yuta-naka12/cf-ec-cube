// modules are defined as an array
// [ module function, map of requires ]
//
// map of requires is short require name -> numeric require
//
// anything defined in a previous bundle is accessed via the
// orig method which is the require for previous bundles
parcelRequire = (function (modules, cache, entry, globalName) {
  // Save the require from previous bundle to this closure if any
  var previousRequire = typeof parcelRequire === 'function' && parcelRequire;
  var nodeRequire = typeof require === 'function' && require;

  function newRequire(name, jumped) {
    if (!cache[name]) {
      if (!modules[name]) {
        // if we cannot find the module within our internal map or
        // cache jump to the current global require ie. the last bundle
        // that was added to the page.
        var currentRequire = typeof parcelRequire === 'function' && parcelRequire;
        if (!jumped && currentRequire) {
          return currentRequire(name, true);
        }

        // If there are other bundles on this page the require from the
        // previous one is saved to 'previousRequire'. Repeat this as
        // many times as there are bundles until the module is found or
        // we exhaust the require chain.
        if (previousRequire) {
          return previousRequire(name, true);
        }

        // Try the node require function if it exists.
        if (nodeRequire && typeof name === 'string') {
          return nodeRequire(name);
        }

        var err = new Error('Cannot find module \'' + name + '\'');
        err.code = 'MODULE_NOT_FOUND';
        throw err;
      }

      localRequire.resolve = resolve;
      localRequire.cache = {};

      var module = cache[name] = new newRequire.Module(name);

      modules[name][0].call(module.exports, localRequire, module, module.exports, this);
    }

    return cache[name].exports;

    function localRequire(x){
      return newRequire(localRequire.resolve(x));
    }

    function resolve(x){
      return modules[name][1][x] || x;
    }
  }

  function Module(moduleName) {
    this.id = moduleName;
    this.bundle = newRequire;
    this.exports = {};
  }

  newRequire.isParcelRequire = true;
  newRequire.Module = Module;
  newRequire.modules = modules;
  newRequire.cache = cache;
  newRequire.parent = previousRequire;
  newRequire.register = function (id, exports) {
    modules[id] = [function (require, module) {
      module.exports = exports;
    }, {}];
  };

  var error;
  for (var i = 0; i < entry.length; i++) {
    try {
      newRequire(entry[i]);
    } catch (e) {
      // Save first error but execute all entries
      if (!error) {
        error = e;
      }
    }
  }

  if (entry.length) {
    // Expose entry point to Node, AMD or browser globals
    // Based on https://github.com/ForbesLindesay/umd/blob/master/template.js
    var mainExports = newRequire(entry[entry.length - 1]);

    // CommonJS
    if (typeof exports === "object" && typeof module !== "undefined") {
      module.exports = mainExports;

    // RequireJS
    } else if (typeof define === "function" && define.amd) {
     define(function () {
       return mainExports;
     });

    // <script>
    } else if (globalName) {
      this[globalName] = mainExports;
    }
  }

  // Override the current require with this new one
  parcelRequire = newRequire;

  if (error) {
    // throw error from earlier, _after updating parcelRequire_
    throw error;
  }

  return newRequire;
})({"../node_modules/regenerator-runtime/runtime.js":[function(require,module,exports) {
var define;
/**
 * Copyright (c) 2014-present, Facebook, Inc.
 *
 * This source code is licensed under the MIT license found in the
 * LICENSE file in the root directory of this source tree.
 */

var runtime = (function (exports) {
  "use strict";

  var Op = Object.prototype;
  var hasOwn = Op.hasOwnProperty;
  var undefined; // More compressible than void 0.
  var $Symbol = typeof Symbol === "function" ? Symbol : {};
  var iteratorSymbol = $Symbol.iterator || "@@iterator";
  var asyncIteratorSymbol = $Symbol.asyncIterator || "@@asyncIterator";
  var toStringTagSymbol = $Symbol.toStringTag || "@@toStringTag";

  function define(obj, key, value) {
    Object.defineProperty(obj, key, {
      value: value,
      enumerable: true,
      configurable: true,
      writable: true
    });
    return obj[key];
  }
  try {
    // IE 8 has a broken Object.defineProperty that only works on DOM objects.
    define({}, "");
  } catch (err) {
    define = function(obj, key, value) {
      return obj[key] = value;
    };
  }

  function wrap(innerFn, outerFn, self, tryLocsList) {
    // If outerFn provided and outerFn.prototype is a Generator, then outerFn.prototype instanceof Generator.
    var protoGenerator = outerFn && outerFn.prototype instanceof Generator ? outerFn : Generator;
    var generator = Object.create(protoGenerator.prototype);
    var context = new Context(tryLocsList || []);

    // The ._invoke method unifies the implementations of the .next,
    // .throw, and .return methods.
    generator._invoke = makeInvokeMethod(innerFn, self, context);

    return generator;
  }
  exports.wrap = wrap;

  // Try/catch helper to minimize deoptimizations. Returns a completion
  // record like context.tryEntries[i].completion. This interface could
  // have been (and was previously) designed to take a closure to be
  // invoked without arguments, but in all the cases we care about we
  // already have an existing method we want to call, so there's no need
  // to create a new function object. We can even get away with assuming
  // the method takes exactly one argument, since that happens to be true
  // in every case, so we don't have to touch the arguments object. The
  // only additional allocation required is the completion record, which
  // has a stable shape and so hopefully should be cheap to allocate.
  function tryCatch(fn, obj, arg) {
    try {
      return { type: "normal", arg: fn.call(obj, arg) };
    } catch (err) {
      return { type: "throw", arg: err };
    }
  }

  var GenStateSuspendedStart = "suspendedStart";
  var GenStateSuspendedYield = "suspendedYield";
  var GenStateExecuting = "executing";
  var GenStateCompleted = "completed";

  // Returning this object from the innerFn has the same effect as
  // breaking out of the dispatch switch statement.
  var ContinueSentinel = {};

  // Dummy constructor functions that we use as the .constructor and
  // .constructor.prototype properties for functions that return Generator
  // objects. For full spec compliance, you may wish to configure your
  // minifier not to mangle the names of these two functions.
  function Generator() {}
  function GeneratorFunction() {}
  function GeneratorFunctionPrototype() {}

  // This is a polyfill for %IteratorPrototype% for environments that
  // don't natively support it.
  var IteratorPrototype = {};
  define(IteratorPrototype, iteratorSymbol, function () {
    return this;
  });

  var getProto = Object.getPrototypeOf;
  var NativeIteratorPrototype = getProto && getProto(getProto(values([])));
  if (NativeIteratorPrototype &&
      NativeIteratorPrototype !== Op &&
      hasOwn.call(NativeIteratorPrototype, iteratorSymbol)) {
    // This environment has a native %IteratorPrototype%; use it instead
    // of the polyfill.
    IteratorPrototype = NativeIteratorPrototype;
  }

  var Gp = GeneratorFunctionPrototype.prototype =
    Generator.prototype = Object.create(IteratorPrototype);
  GeneratorFunction.prototype = GeneratorFunctionPrototype;
  define(Gp, "constructor", GeneratorFunctionPrototype);
  define(GeneratorFunctionPrototype, "constructor", GeneratorFunction);
  GeneratorFunction.displayName = define(
    GeneratorFunctionPrototype,
    toStringTagSymbol,
    "GeneratorFunction"
  );

  // Helper for defining the .next, .throw, and .return methods of the
  // Iterator interface in terms of a single ._invoke method.
  function defineIteratorMethods(prototype) {
    ["next", "throw", "return"].forEach(function(method) {
      define(prototype, method, function(arg) {
        return this._invoke(method, arg);
      });
    });
  }

  exports.isGeneratorFunction = function(genFun) {
    var ctor = typeof genFun === "function" && genFun.constructor;
    return ctor
      ? ctor === GeneratorFunction ||
        // For the native GeneratorFunction constructor, the best we can
        // do is to check its .name property.
        (ctor.displayName || ctor.name) === "GeneratorFunction"
      : false;
  };

  exports.mark = function(genFun) {
    if (Object.setPrototypeOf) {
      Object.setPrototypeOf(genFun, GeneratorFunctionPrototype);
    } else {
      genFun.__proto__ = GeneratorFunctionPrototype;
      define(genFun, toStringTagSymbol, "GeneratorFunction");
    }
    genFun.prototype = Object.create(Gp);
    return genFun;
  };

  // Within the body of any async function, `await x` is transformed to
  // `yield regeneratorRuntime.awrap(x)`, so that the runtime can test
  // `hasOwn.call(value, "__await")` to determine if the yielded value is
  // meant to be awaited.
  exports.awrap = function(arg) {
    return { __await: arg };
  };

  function AsyncIterator(generator, PromiseImpl) {
    function invoke(method, arg, resolve, reject) {
      var record = tryCatch(generator[method], generator, arg);
      if (record.type === "throw") {
        reject(record.arg);
      } else {
        var result = record.arg;
        var value = result.value;
        if (value &&
            typeof value === "object" &&
            hasOwn.call(value, "__await")) {
          return PromiseImpl.resolve(value.__await).then(function(value) {
            invoke("next", value, resolve, reject);
          }, function(err) {
            invoke("throw", err, resolve, reject);
          });
        }

        return PromiseImpl.resolve(value).then(function(unwrapped) {
          // When a yielded Promise is resolved, its final value becomes
          // the .value of the Promise<{value,done}> result for the
          // current iteration.
          result.value = unwrapped;
          resolve(result);
        }, function(error) {
          // If a rejected Promise was yielded, throw the rejection back
          // into the async generator function so it can be handled there.
          return invoke("throw", error, resolve, reject);
        });
      }
    }

    var previousPromise;

    function enqueue(method, arg) {
      function callInvokeWithMethodAndArg() {
        return new PromiseImpl(function(resolve, reject) {
          invoke(method, arg, resolve, reject);
        });
      }

      return previousPromise =
        // If enqueue has been called before, then we want to wait until
        // all previous Promises have been resolved before calling invoke,
        // so that results are always delivered in the correct order. If
        // enqueue has not been called before, then it is important to
        // call invoke immediately, without waiting on a callback to fire,
        // so that the async generator function has the opportunity to do
        // any necessary setup in a predictable way. This predictability
        // is why the Promise constructor synchronously invokes its
        // executor callback, and why async functions synchronously
        // execute code before the first await. Since we implement simple
        // async functions in terms of async generators, it is especially
        // important to get this right, even though it requires care.
        previousPromise ? previousPromise.then(
          callInvokeWithMethodAndArg,
          // Avoid propagating failures to Promises returned by later
          // invocations of the iterator.
          callInvokeWithMethodAndArg
        ) : callInvokeWithMethodAndArg();
    }

    // Define the unified helper method that is used to implement .next,
    // .throw, and .return (see defineIteratorMethods).
    this._invoke = enqueue;
  }

  defineIteratorMethods(AsyncIterator.prototype);
  define(AsyncIterator.prototype, asyncIteratorSymbol, function () {
    return this;
  });
  exports.AsyncIterator = AsyncIterator;

  // Note that simple async functions are implemented on top of
  // AsyncIterator objects; they just return a Promise for the value of
  // the final result produced by the iterator.
  exports.async = function(innerFn, outerFn, self, tryLocsList, PromiseImpl) {
    if (PromiseImpl === void 0) PromiseImpl = Promise;

    var iter = new AsyncIterator(
      wrap(innerFn, outerFn, self, tryLocsList),
      PromiseImpl
    );

    return exports.isGeneratorFunction(outerFn)
      ? iter // If outerFn is a generator, return the full iterator.
      : iter.next().then(function(result) {
          return result.done ? result.value : iter.next();
        });
  };

  function makeInvokeMethod(innerFn, self, context) {
    var state = GenStateSuspendedStart;

    return function invoke(method, arg) {
      if (state === GenStateExecuting) {
        throw new Error("Generator is already running");
      }

      if (state === GenStateCompleted) {
        if (method === "throw") {
          throw arg;
        }

        // Be forgiving, per 25.3.3.3.3 of the spec:
        // https://people.mozilla.org/~jorendorff/es6-draft.html#sec-generatorresume
        return doneResult();
      }

      context.method = method;
      context.arg = arg;

      while (true) {
        var delegate = context.delegate;
        if (delegate) {
          var delegateResult = maybeInvokeDelegate(delegate, context);
          if (delegateResult) {
            if (delegateResult === ContinueSentinel) continue;
            return delegateResult;
          }
        }

        if (context.method === "next") {
          // Setting context._sent for legacy support of Babel's
          // function.sent implementation.
          context.sent = context._sent = context.arg;

        } else if (context.method === "throw") {
          if (state === GenStateSuspendedStart) {
            state = GenStateCompleted;
            throw context.arg;
          }

          context.dispatchException(context.arg);

        } else if (context.method === "return") {
          context.abrupt("return", context.arg);
        }

        state = GenStateExecuting;

        var record = tryCatch(innerFn, self, context);
        if (record.type === "normal") {
          // If an exception is thrown from innerFn, we leave state ===
          // GenStateExecuting and loop back for another invocation.
          state = context.done
            ? GenStateCompleted
            : GenStateSuspendedYield;

          if (record.arg === ContinueSentinel) {
            continue;
          }

          return {
            value: record.arg,
            done: context.done
          };

        } else if (record.type === "throw") {
          state = GenStateCompleted;
          // Dispatch the exception by looping back around to the
          // context.dispatchException(context.arg) call above.
          context.method = "throw";
          context.arg = record.arg;
        }
      }
    };
  }

  // Call delegate.iterator[context.method](context.arg) and handle the
  // result, either by returning a { value, done } result from the
  // delegate iterator, or by modifying context.method and context.arg,
  // setting context.delegate to null, and returning the ContinueSentinel.
  function maybeInvokeDelegate(delegate, context) {
    var method = delegate.iterator[context.method];
    if (method === undefined) {
      // A .throw or .return when the delegate iterator has no .throw
      // method always terminates the yield* loop.
      context.delegate = null;

      if (context.method === "throw") {
        // Note: ["return"] must be used for ES3 parsing compatibility.
        if (delegate.iterator["return"]) {
          // If the delegate iterator has a return method, give it a
          // chance to clean up.
          context.method = "return";
          context.arg = undefined;
          maybeInvokeDelegate(delegate, context);

          if (context.method === "throw") {
            // If maybeInvokeDelegate(context) changed context.method from
            // "return" to "throw", let that override the TypeError below.
            return ContinueSentinel;
          }
        }

        context.method = "throw";
        context.arg = new TypeError(
          "The iterator does not provide a 'throw' method");
      }

      return ContinueSentinel;
    }

    var record = tryCatch(method, delegate.iterator, context.arg);

    if (record.type === "throw") {
      context.method = "throw";
      context.arg = record.arg;
      context.delegate = null;
      return ContinueSentinel;
    }

    var info = record.arg;

    if (! info) {
      context.method = "throw";
      context.arg = new TypeError("iterator result is not an object");
      context.delegate = null;
      return ContinueSentinel;
    }

    if (info.done) {
      // Assign the result of the finished delegate to the temporary
      // variable specified by delegate.resultName (see delegateYield).
      context[delegate.resultName] = info.value;

      // Resume execution at the desired location (see delegateYield).
      context.next = delegate.nextLoc;

      // If context.method was "throw" but the delegate handled the
      // exception, let the outer generator proceed normally. If
      // context.method was "next", forget context.arg since it has been
      // "consumed" by the delegate iterator. If context.method was
      // "return", allow the original .return call to continue in the
      // outer generator.
      if (context.method !== "return") {
        context.method = "next";
        context.arg = undefined;
      }

    } else {
      // Re-yield the result returned by the delegate method.
      return info;
    }

    // The delegate iterator is finished, so forget it and continue with
    // the outer generator.
    context.delegate = null;
    return ContinueSentinel;
  }

  // Define Generator.prototype.{next,throw,return} in terms of the
  // unified ._invoke helper method.
  defineIteratorMethods(Gp);

  define(Gp, toStringTagSymbol, "Generator");

  // A Generator should always return itself as the iterator object when the
  // @@iterator function is called on it. Some browsers' implementations of the
  // iterator prototype chain incorrectly implement this, causing the Generator
  // object to not be returned from this call. This ensures that doesn't happen.
  // See https://github.com/facebook/regenerator/issues/274 for more details.
  define(Gp, iteratorSymbol, function() {
    return this;
  });

  define(Gp, "toString", function() {
    return "[object Generator]";
  });

  function pushTryEntry(locs) {
    var entry = { tryLoc: locs[0] };

    if (1 in locs) {
      entry.catchLoc = locs[1];
    }

    if (2 in locs) {
      entry.finallyLoc = locs[2];
      entry.afterLoc = locs[3];
    }

    this.tryEntries.push(entry);
  }

  function resetTryEntry(entry) {
    var record = entry.completion || {};
    record.type = "normal";
    delete record.arg;
    entry.completion = record;
  }

  function Context(tryLocsList) {
    // The root entry object (effectively a try statement without a catch
    // or a finally block) gives us a place to store values thrown from
    // locations where there is no enclosing try statement.
    this.tryEntries = [{ tryLoc: "root" }];
    tryLocsList.forEach(pushTryEntry, this);
    this.reset(true);
  }

  exports.keys = function(object) {
    var keys = [];
    for (var key in object) {
      keys.push(key);
    }
    keys.reverse();

    // Rather than returning an object with a next method, we keep
    // things simple and return the next function itself.
    return function next() {
      while (keys.length) {
        var key = keys.pop();
        if (key in object) {
          next.value = key;
          next.done = false;
          return next;
        }
      }

      // To avoid creating an additional object, we just hang the .value
      // and .done properties off the next function object itself. This
      // also ensures that the minifier will not anonymize the function.
      next.done = true;
      return next;
    };
  };

  function values(iterable) {
    if (iterable) {
      var iteratorMethod = iterable[iteratorSymbol];
      if (iteratorMethod) {
        return iteratorMethod.call(iterable);
      }

      if (typeof iterable.next === "function") {
        return iterable;
      }

      if (!isNaN(iterable.length)) {
        var i = -1, next = function next() {
          while (++i < iterable.length) {
            if (hasOwn.call(iterable, i)) {
              next.value = iterable[i];
              next.done = false;
              return next;
            }
          }

          next.value = undefined;
          next.done = true;

          return next;
        };

        return next.next = next;
      }
    }

    // Return an iterator with no values.
    return { next: doneResult };
  }
  exports.values = values;

  function doneResult() {
    return { value: undefined, done: true };
  }

  Context.prototype = {
    constructor: Context,

    reset: function(skipTempReset) {
      this.prev = 0;
      this.next = 0;
      // Resetting context._sent for legacy support of Babel's
      // function.sent implementation.
      this.sent = this._sent = undefined;
      this.done = false;
      this.delegate = null;

      this.method = "next";
      this.arg = undefined;

      this.tryEntries.forEach(resetTryEntry);

      if (!skipTempReset) {
        for (var name in this) {
          // Not sure about the optimal order of these conditions:
          if (name.charAt(0) === "t" &&
              hasOwn.call(this, name) &&
              !isNaN(+name.slice(1))) {
            this[name] = undefined;
          }
        }
      }
    },

    stop: function() {
      this.done = true;

      var rootEntry = this.tryEntries[0];
      var rootRecord = rootEntry.completion;
      if (rootRecord.type === "throw") {
        throw rootRecord.arg;
      }

      return this.rval;
    },

    dispatchException: function(exception) {
      if (this.done) {
        throw exception;
      }

      var context = this;
      function handle(loc, caught) {
        record.type = "throw";
        record.arg = exception;
        context.next = loc;

        if (caught) {
          // If the dispatched exception was caught by a catch block,
          // then let that catch block handle the exception normally.
          context.method = "next";
          context.arg = undefined;
        }

        return !! caught;
      }

      for (var i = this.tryEntries.length - 1; i >= 0; --i) {
        var entry = this.tryEntries[i];
        var record = entry.completion;

        if (entry.tryLoc === "root") {
          // Exception thrown outside of any try block that could handle
          // it, so set the completion value of the entire function to
          // throw the exception.
          return handle("end");
        }

        if (entry.tryLoc <= this.prev) {
          var hasCatch = hasOwn.call(entry, "catchLoc");
          var hasFinally = hasOwn.call(entry, "finallyLoc");

          if (hasCatch && hasFinally) {
            if (this.prev < entry.catchLoc) {
              return handle(entry.catchLoc, true);
            } else if (this.prev < entry.finallyLoc) {
              return handle(entry.finallyLoc);
            }

          } else if (hasCatch) {
            if (this.prev < entry.catchLoc) {
              return handle(entry.catchLoc, true);
            }

          } else if (hasFinally) {
            if (this.prev < entry.finallyLoc) {
              return handle(entry.finallyLoc);
            }

          } else {
            throw new Error("try statement without catch or finally");
          }
        }
      }
    },

    abrupt: function(type, arg) {
      for (var i = this.tryEntries.length - 1; i >= 0; --i) {
        var entry = this.tryEntries[i];
        if (entry.tryLoc <= this.prev &&
            hasOwn.call(entry, "finallyLoc") &&
            this.prev < entry.finallyLoc) {
          var finallyEntry = entry;
          break;
        }
      }

      if (finallyEntry &&
          (type === "break" ||
           type === "continue") &&
          finallyEntry.tryLoc <= arg &&
          arg <= finallyEntry.finallyLoc) {
        // Ignore the finally entry if control is not jumping to a
        // location outside the try/catch block.
        finallyEntry = null;
      }

      var record = finallyEntry ? finallyEntry.completion : {};
      record.type = type;
      record.arg = arg;

      if (finallyEntry) {
        this.method = "next";
        this.next = finallyEntry.finallyLoc;
        return ContinueSentinel;
      }

      return this.complete(record);
    },

    complete: function(record, afterLoc) {
      if (record.type === "throw") {
        throw record.arg;
      }

      if (record.type === "break" ||
          record.type === "continue") {
        this.next = record.arg;
      } else if (record.type === "return") {
        this.rval = this.arg = record.arg;
        this.method = "return";
        this.next = "end";
      } else if (record.type === "normal" && afterLoc) {
        this.next = afterLoc;
      }

      return ContinueSentinel;
    },

    finish: function(finallyLoc) {
      for (var i = this.tryEntries.length - 1; i >= 0; --i) {
        var entry = this.tryEntries[i];
        if (entry.finallyLoc === finallyLoc) {
          this.complete(entry.completion, entry.afterLoc);
          resetTryEntry(entry);
          return ContinueSentinel;
        }
      }
    },

    "catch": function(tryLoc) {
      for (var i = this.tryEntries.length - 1; i >= 0; --i) {
        var entry = this.tryEntries[i];
        if (entry.tryLoc === tryLoc) {
          var record = entry.completion;
          if (record.type === "throw") {
            var thrown = record.arg;
            resetTryEntry(entry);
          }
          return thrown;
        }
      }

      // The context.catch method must only be called with a location
      // argument that corresponds to a known catch block.
      throw new Error("illegal catch attempt");
    },

    delegateYield: function(iterable, resultName, nextLoc) {
      this.delegate = {
        iterator: values(iterable),
        resultName: resultName,
        nextLoc: nextLoc
      };

      if (this.method === "next") {
        // Deliberately forget the last sent value so that we don't
        // accidentally pass it on to the delegate.
        this.arg = undefined;
      }

      return ContinueSentinel;
    }
  };

  // Regardless of whether this script is executing as a CommonJS module
  // or not, return the runtime object so that we can declare the variable
  // regeneratorRuntime in the outer scope, which allows this module to be
  // injected easily by `bin/regenerator --include-runtime script.js`.
  return exports;

}(
  // If this script is executing as a CommonJS module, use module.exports
  // as the regeneratorRuntime namespace. Otherwise create a new empty
  // object. Either way, the resulting object will be used to initialize
  // the regeneratorRuntime variable at the top of this file.
  typeof module === "object" ? module.exports : {}
));

try {
  regeneratorRuntime = runtime;
} catch (accidentalStrictMode) {
  // This module should not be running in strict mode, so the above
  // assignment should always work unless something is misconfigured. Just
  // in case runtime.js accidentally runs in strict mode, in modern engines
  // we can explicitly access globalThis. In older engines we can escape
  // strict mode using a global Function call. This could conceivably fail
  // if a Content Security Policy forbids using Function, but in that case
  // the proper solution is to fix the accidental strict mode problem. If
  // you've misconfigured your bundler to force strict mode and applied a
  // CSP to forbid Function, and you're not willing to fix either of those
  // problems, please detail your unique predicament in a GitHub issue.
  if (typeof globalThis === "object") {
    globalThis.regeneratorRuntime = runtime;
  } else {
    Function("r", "regeneratorRuntime = r")(runtime);
  }
}

},{}],"js/components/forEachPolyfill.ts":[function(require,module,exports) {
"use strict";
/**
* Foreach polyfill for browsers
*/

Object.defineProperty(exports, "__esModule", {
  value: true
});

var foreachPolyfill = function foreachPolyfill() {
  if (window.NodeList && !NodeList.prototype.forEach) {
    NodeList.prototype.forEach = function (callback, thisArg) {
      thisArg = thisArg || window;

      for (var i = 0; i < this.length; i++) {
        callback.call(thisArg, this[i], i, this);
      }
    };
  }
};

exports.default = foreachPolyfill;
},{}],"js/components/intializeBanner.ts":[function(require,module,exports) {
"use strict";
/**
*  Intializes flickity in Banner
*/

Object.defineProperty(exports, "__esModule", {
  value: true
});

var intializeBanner = function intializeBanner() {
  var banner = document.querySelector('.js-banner');
  var bacteria = document.querySelector('.js-bacteria');

  if (banner) {
    //@ts-ignore
    var flickity = new Flickity(banner, {
      wrapAround: true,
      pageDots: true,
      contain: true,
      adaptiveHeight: false,
      prevNextButtons: false,
      pauseAutoPlayOnHover: false,
      draggable: false,
      autoPlay: 5000,
      fade: true,
      lazyLoad: true
    });
    banner.addEventListener('click', function () {
      flickity.playPlayer();
    });
  }
};

exports.default = intializeBanner;
},{}],"js/components/toggleMenu.ts":[function(require,module,exports) {
"use strict";
/**
 * Toggle menu on click
 */

Object.defineProperty(exports, "__esModule", {
  value: true
});

var toggleMenu = function toggleMenu() {
  var toggle = document.querySelector('#menu-toggle');
  var toggleSp = document.querySelector('#menu-toggle-sp');
  var nav = document.querySelector('nav');
  toggle === null || toggle === void 0 ? void 0 : toggle.addEventListener('click', function () {
    toggle.classList.toggle('active');
    nav === null || nav === void 0 ? void 0 : nav.classList.toggle('active');
  });
  toggleSp === null || toggleSp === void 0 ? void 0 : toggleSp.addEventListener('click', function () {
    toggleSp.classList.toggle('active');
    nav === null || nav === void 0 ? void 0 : nav.classList.toggle('active');
  });

  if (screen.width < 768) {
    var spMenu = document.querySelectorAll('.sidemenu__menu-list');
    spMenu.forEach(function (element) {
      element.addEventListener('click', function () {
        var _a;

        element.classList.toggle('active');
        (_a = element.nextElementSibling) === null || _a === void 0 ? void 0 : _a.classList.toggle('active');
      });
    });
  }
};

exports.default = toggleMenu;
},{}],"js/components/faqAccordion.ts":[function(require,module,exports) {
"use strict";
/**
 *  FAQ Accordion component
 */

Object.defineProperty(exports, "__esModule", {
  value: true
});

var faqAccordion = function faqAccordion() {
  var questionDivEl = document.querySelectorAll('.js-faq__item-question');
  questionDivEl.forEach(function (element) {
    element.addEventListener('click', function (event) {
      var carretDivEl = element.lastElementChild;
      var answerDivEl = element.nextElementSibling;
      carretDivEl === null || carretDivEl === void 0 ? void 0 : carretDivEl.classList.toggle('faq__item-question-carret--active');
      answerDivEl === null || answerDivEl === void 0 ? void 0 : answerDivEl.classList.toggle('faq__item-answer--active');
    });
  });
};

exports.default = faqAccordion;
},{}],"js/components/getZip.ts":[function(require,module,exports) {
"use strict";
/**
 * Get zip using ajaxzip3
 */

Object.defineProperty(exports, "__esModule", {
  value: true
});

var getZip = function getZip() {
  var getZip = document.querySelector('.form__button-zip');

  if (getZip) {
    getZip.addEventListener('click', function (event) {
      // @ts-ignore
      AjaxZip3.zip2addr("pamphlet[postal_code]", "", "pamphlet[address][pref]", "pamphlet[address][addr01]");
      return false;
    });
  }
};

exports.default = getZip;
},{}],"js/components/showMore.ts":[function(require,module,exports) {
"use strict";
/**
 * Show more on click
 */

function _toConsumableArray(arr) { return _arrayWithoutHoles(arr) || _iterableToArray(arr) || _unsupportedIterableToArray(arr) || _nonIterableSpread(); }

function _nonIterableSpread() { throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _iterableToArray(iter) { if (typeof Symbol !== "undefined" && iter[Symbol.iterator] != null || iter["@@iterator"] != null) return Array.from(iter); }

function _arrayWithoutHoles(arr) { if (Array.isArray(arr)) return _arrayLikeToArray(arr); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) { arr2[i] = arr[i]; } return arr2; }

Object.defineProperty(exports, "__esModule", {
  value: true
});

var showMore = function showMore() {
  var loadMore = document.getElementById('loadmore');

  var hid = _toConsumableArray(document.querySelectorAll('.news-list__item.hidden'));

  hid.splice(0, 5).forEach(function (elem) {
    return elem.classList.remove('hidden');
  });
  loadMore === null || loadMore === void 0 ? void 0 : loadMore.addEventListener('click', function (e) {
    e.preventDefault();
    hid.splice(0, 5).forEach(function (elem) {
      return elem.classList.remove('hidden');
    });

    if (hid.length == 0) {
      loadMore.classList.add('hidden');
    }
  });
};

exports.default = showMore;
},{}],"js/components/clampText.ts":[function(require,module,exports) {
"use strict";
/**
* Truncate Text
*/

Object.defineProperty(exports, "__esModule", {
  value: true
});

var clampText = function clampText() {
  var len = 60;
  var p = document.querySelectorAll('.truncateMe');

  if (p) {
    p.forEach(function (element) {
      var p = element;
      var trunc = p.innerHTML;

      if (trunc.length > len) {
        trunc = trunc.substring(0, len);
        trunc = trunc.replace(/\w+$/, '');
        trunc += '<p' + p.innerHTML + '\');">' + '...<\/p>';
        p.innerHTML = trunc;
      }
    });
  }

  ;
};

exports.default = clampText;
},{}],"js/components/sideSublistToggle.ts":[function(require,module,exports) {
"use strict";
/**
* Truncate Text
*/

Object.defineProperty(exports, "__esModule", {
  value: true
});

var sideSublistToggle = function sideSublistToggle() {
  var items = document.querySelectorAll('.sublist_toggle_js');
  var menus = document.querySelectorAll('.sublist_menu_js');

  if (items.length > 0 && menus.length > 0) {
    items.forEach(function (element) {
      element.addEventListener('click', function () {
        menus.forEach(function (element2) {
          if (element2.getAttribute('sublist') == element.getAttribute('sublist')) {
            element2.classList.toggle('active');
            element.classList.toggle('active');
          }
        });
      });
    });
  }
};

exports.default = sideSublistToggle;
},{}],"js/components/tabToggle.ts":[function(require,module,exports) {
"use strict";
/**
* Truncate Text
*/

Object.defineProperty(exports, "__esModule", {
  value: true
});

var tabToggle = function tabToggle() {
  var list = document.querySelectorAll('.js-tabbing');

  if (list.length > 0) {
    list.forEach(function (list_item) {
      var items = list_item.querySelectorAll('.js-tab-toggle');
      var menus = list_item.querySelectorAll('.js-tab-menu');

      if (items.length > 0 && menus.length > 0) {
        items.forEach(function (element) {
          element.addEventListener('click', function () {
            list.forEach(function (list_element) {
              if (list_element.getAttribute('link-tab') != null && element.getAttribute('link-tab') != null) {
                list_element.classList.remove('active');

                if (list_element.getAttribute('link-tab') == element.getAttribute('link-tab')) {
                  list_element.classList.add('active');
                }
              }
            });
            items.forEach(function (element_item) {
              element_item.classList.remove('active');
            });
            menus.forEach(function (element_menus) {
              element_menus.classList.remove('active');
            });
            menus.forEach(function (element2) {
              if (element2.getAttribute('tabid') == element.getAttribute('tabid')) {
                element2.classList.add('active');
                element.classList.add('active');
              }
            });
          });
        });
      }
    });
  }
};

exports.default = tabToggle;
},{}],"js/components/jsonPostClass.ts":[function(require,module,exports) {
"use strict";

function _typeof(obj) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (obj) { return typeof obj; } : function (obj) { return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }, _typeof(obj); }

function _slicedToArray(arr, i) { return _arrayWithHoles(arr) || _iterableToArrayLimit(arr, i) || _unsupportedIterableToArray(arr, i) || _nonIterableRest(); }

function _nonIterableRest() { throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) { arr2[i] = arr[i]; } return arr2; }

function _iterableToArrayLimit(arr, i) { var _i = arr == null ? null : typeof Symbol !== "undefined" && arr[Symbol.iterator] || arr["@@iterator"]; if (_i == null) return; var _arr = []; var _n = true; var _d = false; var _s, _e; try { for (_i = _i.call(arr); !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"] != null) _i["return"](); } finally { if (_d) throw _e; } } return _arr; }

function _arrayWithHoles(arr) { if (Array.isArray(arr)) return arr; }

function _regeneratorRuntime() { "use strict"; /*! regenerator-runtime -- Copyright (c) 2014-present, Facebook, Inc. -- license (MIT): https://github.com/facebook/regenerator/blob/main/LICENSE */ _regeneratorRuntime = function _regeneratorRuntime() { return exports; }; var exports = {}, Op = Object.prototype, hasOwn = Op.hasOwnProperty, $Symbol = "function" == typeof Symbol ? Symbol : {}, iteratorSymbol = $Symbol.iterator || "@@iterator", asyncIteratorSymbol = $Symbol.asyncIterator || "@@asyncIterator", toStringTagSymbol = $Symbol.toStringTag || "@@toStringTag"; function define(obj, key, value) { return Object.defineProperty(obj, key, { value: value, enumerable: !0, configurable: !0, writable: !0 }), obj[key]; } try { define({}, ""); } catch (err) { define = function define(obj, key, value) { return obj[key] = value; }; } function wrap(innerFn, outerFn, self, tryLocsList) { var protoGenerator = outerFn && outerFn.prototype instanceof Generator ? outerFn : Generator, generator = Object.create(protoGenerator.prototype), context = new Context(tryLocsList || []); return generator._invoke = function (innerFn, self, context) { var state = "suspendedStart"; return function (method, arg) { if ("executing" === state) throw new Error("Generator is already running"); if ("completed" === state) { if ("throw" === method) throw arg; return doneResult(); } for (context.method = method, context.arg = arg;;) { var delegate = context.delegate; if (delegate) { var delegateResult = maybeInvokeDelegate(delegate, context); if (delegateResult) { if (delegateResult === ContinueSentinel) continue; return delegateResult; } } if ("next" === context.method) context.sent = context._sent = context.arg;else if ("throw" === context.method) { if ("suspendedStart" === state) throw state = "completed", context.arg; context.dispatchException(context.arg); } else "return" === context.method && context.abrupt("return", context.arg); state = "executing"; var record = tryCatch(innerFn, self, context); if ("normal" === record.type) { if (state = context.done ? "completed" : "suspendedYield", record.arg === ContinueSentinel) continue; return { value: record.arg, done: context.done }; } "throw" === record.type && (state = "completed", context.method = "throw", context.arg = record.arg); } }; }(innerFn, self, context), generator; } function tryCatch(fn, obj, arg) { try { return { type: "normal", arg: fn.call(obj, arg) }; } catch (err) { return { type: "throw", arg: err }; } } exports.wrap = wrap; var ContinueSentinel = {}; function Generator() {} function GeneratorFunction() {} function GeneratorFunctionPrototype() {} var IteratorPrototype = {}; define(IteratorPrototype, iteratorSymbol, function () { return this; }); var getProto = Object.getPrototypeOf, NativeIteratorPrototype = getProto && getProto(getProto(values([]))); NativeIteratorPrototype && NativeIteratorPrototype !== Op && hasOwn.call(NativeIteratorPrototype, iteratorSymbol) && (IteratorPrototype = NativeIteratorPrototype); var Gp = GeneratorFunctionPrototype.prototype = Generator.prototype = Object.create(IteratorPrototype); function defineIteratorMethods(prototype) { ["next", "throw", "return"].forEach(function (method) { define(prototype, method, function (arg) { return this._invoke(method, arg); }); }); } function AsyncIterator(generator, PromiseImpl) { function invoke(method, arg, resolve, reject) { var record = tryCatch(generator[method], generator, arg); if ("throw" !== record.type) { var result = record.arg, value = result.value; return value && "object" == _typeof(value) && hasOwn.call(value, "__await") ? PromiseImpl.resolve(value.__await).then(function (value) { invoke("next", value, resolve, reject); }, function (err) { invoke("throw", err, resolve, reject); }) : PromiseImpl.resolve(value).then(function (unwrapped) { result.value = unwrapped, resolve(result); }, function (error) { return invoke("throw", error, resolve, reject); }); } reject(record.arg); } var previousPromise; this._invoke = function (method, arg) { function callInvokeWithMethodAndArg() { return new PromiseImpl(function (resolve, reject) { invoke(method, arg, resolve, reject); }); } return previousPromise = previousPromise ? previousPromise.then(callInvokeWithMethodAndArg, callInvokeWithMethodAndArg) : callInvokeWithMethodAndArg(); }; } function maybeInvokeDelegate(delegate, context) { var method = delegate.iterator[context.method]; if (undefined === method) { if (context.delegate = null, "throw" === context.method) { if (delegate.iterator.return && (context.method = "return", context.arg = undefined, maybeInvokeDelegate(delegate, context), "throw" === context.method)) return ContinueSentinel; context.method = "throw", context.arg = new TypeError("The iterator does not provide a 'throw' method"); } return ContinueSentinel; } var record = tryCatch(method, delegate.iterator, context.arg); if ("throw" === record.type) return context.method = "throw", context.arg = record.arg, context.delegate = null, ContinueSentinel; var info = record.arg; return info ? info.done ? (context[delegate.resultName] = info.value, context.next = delegate.nextLoc, "return" !== context.method && (context.method = "next", context.arg = undefined), context.delegate = null, ContinueSentinel) : info : (context.method = "throw", context.arg = new TypeError("iterator result is not an object"), context.delegate = null, ContinueSentinel); } function pushTryEntry(locs) { var entry = { tryLoc: locs[0] }; 1 in locs && (entry.catchLoc = locs[1]), 2 in locs && (entry.finallyLoc = locs[2], entry.afterLoc = locs[3]), this.tryEntries.push(entry); } function resetTryEntry(entry) { var record = entry.completion || {}; record.type = "normal", delete record.arg, entry.completion = record; } function Context(tryLocsList) { this.tryEntries = [{ tryLoc: "root" }], tryLocsList.forEach(pushTryEntry, this), this.reset(!0); } function values(iterable) { if (iterable) { var iteratorMethod = iterable[iteratorSymbol]; if (iteratorMethod) return iteratorMethod.call(iterable); if ("function" == typeof iterable.next) return iterable; if (!isNaN(iterable.length)) { var i = -1, next = function next() { for (; ++i < iterable.length;) { if (hasOwn.call(iterable, i)) return next.value = iterable[i], next.done = !1, next; } return next.value = undefined, next.done = !0, next; }; return next.next = next; } } return { next: doneResult }; } function doneResult() { return { value: undefined, done: !0 }; } return GeneratorFunction.prototype = GeneratorFunctionPrototype, define(Gp, "constructor", GeneratorFunctionPrototype), define(GeneratorFunctionPrototype, "constructor", GeneratorFunction), GeneratorFunction.displayName = define(GeneratorFunctionPrototype, toStringTagSymbol, "GeneratorFunction"), exports.isGeneratorFunction = function (genFun) { var ctor = "function" == typeof genFun && genFun.constructor; return !!ctor && (ctor === GeneratorFunction || "GeneratorFunction" === (ctor.displayName || ctor.name)); }, exports.mark = function (genFun) { return Object.setPrototypeOf ? Object.setPrototypeOf(genFun, GeneratorFunctionPrototype) : (genFun.__proto__ = GeneratorFunctionPrototype, define(genFun, toStringTagSymbol, "GeneratorFunction")), genFun.prototype = Object.create(Gp), genFun; }, exports.awrap = function (arg) { return { __await: arg }; }, defineIteratorMethods(AsyncIterator.prototype), define(AsyncIterator.prototype, asyncIteratorSymbol, function () { return this; }), exports.AsyncIterator = AsyncIterator, exports.async = function (innerFn, outerFn, self, tryLocsList, PromiseImpl) { void 0 === PromiseImpl && (PromiseImpl = Promise); var iter = new AsyncIterator(wrap(innerFn, outerFn, self, tryLocsList), PromiseImpl); return exports.isGeneratorFunction(outerFn) ? iter : iter.next().then(function (result) { return result.done ? result.value : iter.next(); }); }, defineIteratorMethods(Gp), define(Gp, toStringTagSymbol, "Generator"), define(Gp, iteratorSymbol, function () { return this; }), define(Gp, "toString", function () { return "[object Generator]"; }), exports.keys = function (object) { var keys = []; for (var key in object) { keys.push(key); } return keys.reverse(), function next() { for (; keys.length;) { var key = keys.pop(); if (key in object) return next.value = key, next.done = !1, next; } return next.done = !0, next; }; }, exports.values = values, Context.prototype = { constructor: Context, reset: function reset(skipTempReset) { if (this.prev = 0, this.next = 0, this.sent = this._sent = undefined, this.done = !1, this.delegate = null, this.method = "next", this.arg = undefined, this.tryEntries.forEach(resetTryEntry), !skipTempReset) for (var name in this) { "t" === name.charAt(0) && hasOwn.call(this, name) && !isNaN(+name.slice(1)) && (this[name] = undefined); } }, stop: function stop() { this.done = !0; var rootRecord = this.tryEntries[0].completion; if ("throw" === rootRecord.type) throw rootRecord.arg; return this.rval; }, dispatchException: function dispatchException(exception) { if (this.done) throw exception; var context = this; function handle(loc, caught) { return record.type = "throw", record.arg = exception, context.next = loc, caught && (context.method = "next", context.arg = undefined), !!caught; } for (var i = this.tryEntries.length - 1; i >= 0; --i) { var entry = this.tryEntries[i], record = entry.completion; if ("root" === entry.tryLoc) return handle("end"); if (entry.tryLoc <= this.prev) { var hasCatch = hasOwn.call(entry, "catchLoc"), hasFinally = hasOwn.call(entry, "finallyLoc"); if (hasCatch && hasFinally) { if (this.prev < entry.catchLoc) return handle(entry.catchLoc, !0); if (this.prev < entry.finallyLoc) return handle(entry.finallyLoc); } else if (hasCatch) { if (this.prev < entry.catchLoc) return handle(entry.catchLoc, !0); } else { if (!hasFinally) throw new Error("try statement without catch or finally"); if (this.prev < entry.finallyLoc) return handle(entry.finallyLoc); } } } }, abrupt: function abrupt(type, arg) { for (var i = this.tryEntries.length - 1; i >= 0; --i) { var entry = this.tryEntries[i]; if (entry.tryLoc <= this.prev && hasOwn.call(entry, "finallyLoc") && this.prev < entry.finallyLoc) { var finallyEntry = entry; break; } } finallyEntry && ("break" === type || "continue" === type) && finallyEntry.tryLoc <= arg && arg <= finallyEntry.finallyLoc && (finallyEntry = null); var record = finallyEntry ? finallyEntry.completion : {}; return record.type = type, record.arg = arg, finallyEntry ? (this.method = "next", this.next = finallyEntry.finallyLoc, ContinueSentinel) : this.complete(record); }, complete: function complete(record, afterLoc) { if ("throw" === record.type) throw record.arg; return "break" === record.type || "continue" === record.type ? this.next = record.arg : "return" === record.type ? (this.rval = this.arg = record.arg, this.method = "return", this.next = "end") : "normal" === record.type && afterLoc && (this.next = afterLoc), ContinueSentinel; }, finish: function finish(finallyLoc) { for (var i = this.tryEntries.length - 1; i >= 0; --i) { var entry = this.tryEntries[i]; if (entry.finallyLoc === finallyLoc) return this.complete(entry.completion, entry.afterLoc), resetTryEntry(entry), ContinueSentinel; } }, catch: function _catch(tryLoc) { for (var i = this.tryEntries.length - 1; i >= 0; --i) { var entry = this.tryEntries[i]; if (entry.tryLoc === tryLoc) { var record = entry.completion; if ("throw" === record.type) { var thrown = record.arg; resetTryEntry(entry); } return thrown; } } throw new Error("illegal catch attempt"); }, delegateYield: function delegateYield(iterable, resultName, nextLoc) { return this.delegate = { iterator: values(iterable), resultName: resultName, nextLoc: nextLoc }, "next" === this.method && (this.arg = undefined), ContinueSentinel; } }, exports; }

function asyncGeneratorStep(gen, resolve, reject, _next, _throw, key, arg) { try { var info = gen[key](arg); var value = info.value; } catch (error) { reject(error); return; } if (info.done) { resolve(value); } else { Promise.resolve(value).then(_next, _throw); } }

function _asyncToGenerator(fn) { return function () { var self = this, args = arguments; return new Promise(function (resolve, reject) { var gen = fn.apply(self, args); function _next(value) { asyncGeneratorStep(gen, resolve, reject, _next, _throw, "next", value); } function _throw(err) { asyncGeneratorStep(gen, resolve, reject, _next, _throw, "throw", err); } _next(undefined); }); }; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); Object.defineProperty(Constructor, "prototype", { writable: false }); return Constructor; }

Object.defineProperty(exports, "__esModule", {
  value: true
});
exports.jsonPostClass = void 0;

var jsonPostClass = /*#__PURE__*/function () {
  function jsonPostClass(json_contents) {
    _classCallCheck(this, jsonPostClass);

    this.jsonPostContent = json_contents;
  }

  _createClass(jsonPostClass, [{
    key: "getJsonContents",
    value: function () {
      var _getJsonContents = _asyncToGenerator( /*#__PURE__*/_regeneratorRuntime().mark(function _callee(jsonfile) {
        var url, res, data;
        return _regeneratorRuntime().wrap(function _callee$(_context) {
          while (1) {
            switch (_context.prev = _context.next) {
              case 0:
                url = jsonfile;
                _context.prev = 1;
                _context.next = 4;
                return fetch(url);

              case 4:
                res = _context.sent;
                _context.next = 7;
                return res.json();

              case 7:
                data = _context.sent;
                data = JSON.stringify(data);
                data = JSON.parse(data);
                return _context.abrupt("return", data);

              case 13:
                _context.prev = 13;
                _context.t0 = _context["catch"](1);
                console.log(_context.t0);

              case 16:
              case "end":
                return _context.stop();
            }
          }
        }, _callee, null, [[1, 13]]);
      }));

      function getJsonContents(_x) {
        return _getJsonContents.apply(this, arguments);
      }

      return getJsonContents;
    }()
  }, {
    key: "getJSONXHR",
    value: function getJSONXHR(json_file_name) {
      return new Promise(function (resolve, reject) {
        var xhr = new XMLHttpRequest();

        xhr.onload = function () {
          console.log(this.responseText);
          resolve(this.responseText);
        };

        xhr.onerror = reject;
        xhr.open('GET', json_file_name);
        xhr.send();
      });
    }
  }, {
    key: "getPostList",
    value: function getPostList(keyValue) {
      var option = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {};

      if (keyValue != null || keyValue != '') {
        var item = this.jsonPostContent;
        var return_val;
        Object.keys(item).forEach(function (key) {
          if (keyValue == key) {
            if (Object.keys(option).length > 0) {
              var filter_items = item[key].post;

              if (option.sort == 'date-desc') {
                var vals = Object.entries(filter_items).sort(function (a, b) {
                  return Date.parse(b[1].date) - Date.parse(a[1].date);
                }).reduce(function (acc, _ref) {
                  var _ref2 = _slicedToArray(_ref, 2),
                      k = _ref2[0],
                      v = _ref2[1];

                  return acc[k] = v, acc;
                }, {});
                filter_items = vals;
              }

              if (option.category_id && option.category_id > 0) {
                var categorized = {};
                Object.keys(filter_items).map(function (key_2) {
                  if (filter_items[key_2].category == option.category_id) {
                    categorized[key_2] = filter_items[key_2]; //Object.assign(categorized, {key_2 : filter_items[key_2]});
                  }
                });
                filter_items = categorized;
              }

              return_val = filter_items;
            } else {
              return_val = item[key].post;
            }
          }
        });
        return return_val;
      } else {
        return 0;
      }
    }
  }, {
    key: "getPostDetails",
    value: function getPostDetails(post_id, post_key) {
      var return_val = false;

      if (post_id && post_key) {
        var item = this.jsonPostContent;
        Object.keys(item).forEach(function (key) {
          if (key == post_key) {
            var item_posts = item[key];
            Object.keys(item_posts.post).forEach(function (key_2) {
              if (key_2 == post_id) {
                return_val = item_posts.post[key_2];
              }
            });
          }
        });
      }

      return return_val;
    }
  }, {
    key: "getPostTypeCount",
    value: function getPostTypeCount() {
      return Object.keys(this.jsonPostContent).length;
    }
  }, {
    key: "getPostListCount",
    value: function getPostListCount(post_list) {
      return Object.keys(post_list).length;
    }
  }, {
    key: "getCategories",
    value: function getCategories(post_key) {
      var _this = this;

      var category_list;
      Object.keys(this.jsonPostContent).every(function (key) {
        if (key == post_key) {
          category_list = _this.jsonPostContent[key].categories;
        } else category_list = false;
      });
      return category_list;
    }
  }, {
    key: "getPostCategory",
    value: function getPostCategory(post_key, categ_id, type) {
      var _this2 = this;

      var category_return;
      Object.keys(this.jsonPostContent).every(function (key) {
        if (key == post_key) {
          Object.keys(_this2.jsonPostContent[key].categories).forEach(function (cat_key) {
            var cat_id = _this2.jsonPostContent[key].categories[cat_key].id;

            if (cat_id == categ_id && type == 'name') {
              category_return = _this2.jsonPostContent[key].categories[cat_key].name;
            } else if (cat_id == categ_id && type == 'id') {
              category_return = cat_id;
            }
          });
        } else category_return = null;
      });
      return category_return;
    }
  }]);

  return jsonPostClass;
}();

exports.jsonPostClass = jsonPostClass;
},{}],"js/components/jsonNews.ts":[function(require,module,exports) {
"use strict";
/**
* Json Based news
*/

Object.defineProperty(exports, "__esModule", {
  value: true
});

var jsonPostClass_1 = require("./jsonPostClass");

function loadContents(url) {
  return new Promise(function (resolve, reject) {
    var xhr = new XMLHttpRequest();

    xhr.onload = function () {
      resolve(this.responseText); //--Call base class--{START}//

      var jsonNews = new jsonPostClass_1.jsonPostClass(JSON.parse(this.responseText)); //--Call base class--{END}//
      //--Fixed vars--{START}//

      var main_base_url = '';
      main_base_url = 'https://captainfoods.co.jp/stg/cf2022/'; //Uncomment the code above when building and uploading to FTP or Prod server
      //--Fixed vars--{END}//
      //--Declaration of usable functions--{START}//

      function getPosts(postList, list) {
        var options = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : [];

        if (jsonNews.getPostListCount(postList) >= options.page_limit && jsonNews.getPostListCount(postList) > options.page_start) {
          Object.keys(postList).slice(options.page_start, options.page_limit).forEach(function (key) {
            var postItem = postList[key];
            list.innerHTML = list.innerHTML + '<a class="news-list__item" href="' + main_base_url + 'news-details.html?post_id=' + key + '&post_key=news">' + '<div class="news-list__item-date">' + postItem.date + '</div>' + '<div class="news-list__item-content"> ' + '<div class="news-list__item-category">' + jsonNews.getPostCategory('news', parseInt(postItem.category), 'name') + '</div>' + '<div class="news-list__item-excerpt">' + postItem.title + '</div>' + '</div>' + '</a>';
          });
        } else {
          Object.keys(postList).forEach(function (key) {
            var postItem = postList[key];
            list.innerHTML = list.innerHTML + '<a class="news-list__item" href="' + main_base_url + 'news-details.html?post_id=' + key + '&post_key=news">' + '<div class="news-list__item-date">' + postItem.date + '</div>' + '<div class="news-list__item-content"> ' + '<div class="news-list__item-category">' + jsonNews.getPostCategory('news', parseInt(postItem.category), 'name') + '</div>' + '<div class="news-list__item-excerpt">' + postItem.title + '</div>' + '</div>' + '</a>';
          });
        }
      } //--Declaration of usable functions--{END}//
      //--MAIN{START}//


      if (jsonNews) {
        //--ALL POST PAGE--{START}//
        var postList = jsonNews.getPostList('news', {
          'sort': 'date-desc'
        });
        var list = document.querySelector('.js-load-post');
        var more_button = document.querySelector('.js-more-post');
        var post_list_total = jsonNews.getPostListCount(postList);

        if (list && post_list_total > 0) {
          var page_limit = list.getAttribute('paged_limit');
          page_limit = parseInt(page_limit);

          if (list.getAttribute('paged') == 'paged' && page_limit && page_limit < post_list_total && more_button) {
            var curr_page = parseInt(page_limit);
            var options = {
              'page_start': 0,
              'page_limit': curr_page
            };
            getPosts(postList, list, options);
            more_button.addEventListener('click', function () {
              if (curr_page + page_limit >= post_list_total) {
                options = {
                  'page_start': curr_page,
                  'page_limit': post_list_total
                };
                curr_page = post_list_total;
                more_button.remove();
              } else {
                options = {
                  'page_start': curr_page,
                  'page_limit': curr_page + page_limit
                };
                curr_page = curr_page + page_limit;
              }

              getPosts(postList, list, options);
            });
          } else {
            getPosts(postList, list);
            more_button.remove();
          }
        } //--ALL POST PAGE--{END}//
        //--CATEGORY ITEMS GENERATION--{START}//


        var categ_list = document.querySelector('.js-load-categories');
        var categ_list_json = jsonNews.getCategories('news');

        if (categ_list && categ_list_json) {
          Object.keys(categ_list_json).forEach(function (key) {
            var categ_item = categ_list_json[key];
            categ_list.innerHTML = categ_list.innerHTML + '<a class="news-list__filter-button js-categ-change" categ_id="' + categ_item.id + '" href="' + main_base_url + 'news-list-category.html?categ_id=' + categ_item.id + '">' + categ_item.name + '</a>';
          });
        } //--CATEGORY ITEMS GENERATION--{END}//
        //--CATEGORY PAGE--{START}//


        var base_url = window.location.href;
        var categ_id = base_url.substring(base_url.indexOf('categ_id=') + 'categ_id='.length, base_url.length);
        var categ_urls = document.querySelectorAll('.js-categ-change');

        if (categ_urls) {
          categ_urls.forEach(function (element) {
            element.classList.remove('news-list__filter-button--active');

            if (element.getAttribute('categ_id') == categ_id) {
              element.classList.add('news-list__filter-button--active');
            }
          });
        }

        var list_category = document.querySelector('.js-load-post-categories');
        var postList_category = jsonNews.getPostList('news', {
          'sort': 'date-desc',
          'category_id': categ_id
        });
        var more_button_category = document.querySelector('.js-more-post-category');
        var post_list_total_category = jsonNews.getPostListCount(postList_category);

        if (list_category && post_list_total_category > 0) {
          var page_limit = list_category.getAttribute('paged_limit');
          page_limit = parseInt(page_limit);

          if (list_category.getAttribute('paged') == 'paged' && page_limit && page_limit < post_list_total_category && more_button_category) {
            var curr_page = parseInt(page_limit);
            var options = {
              'page_start': 0,
              'page_limit': curr_page
            };
            getPosts(postList_category, list_category, options);
            more_button_category.addEventListener('click', function () {
              if (curr_page + page_limit >= post_list_total_category) {
                options = {
                  'page_start': curr_page,
                  'page_limit': post_list_total_category
                };
                curr_page = post_list_total_category;
                more_button_category.remove();
              } else {
                options = {
                  'page_start': curr_page,
                  'page_limit': curr_page + page_limit
                };
                curr_page = curr_page + page_limit;
              }

              getPosts(postList_category, list_category, options);
            });
          } else {
            getPosts(postList_category, list_category);
            more_button_category.remove();
          }
        } //--CATEGORY PAGE--{END}//
        //--POST DETAIL PAGE--{START}//


        var base_url_post = window.location.href;
        var base_url_params = base_url_post.substring(base_url.indexOf('?') + 1, base_url_post.length).split('&');
        var post_id = '';
        var post_key = '';
        base_url_params.forEach(function (element) {
          if (element.substring(0, 'post_id='.length) == 'post_id=') {
            post_id = element.substring(element.substring(0, 'post_id='.length).length, element.length);
          } else if (element.substring(0, 'post_key='.length) == 'post_key=') {
            post_key = element.substring(element.substring(0, 'post_key='.length).length, element.length);
          }
        });
        var post_contents = jsonNews.getPostDetails(post_id, post_key);
        var post_html_date = document.querySelector('#news-post-date');
        var post_html_category = document.querySelector('#news-post-category');
        var post_html_title = document.querySelector('#news-post-title');
        var post_html_mainvisual = document.querySelector('#news-post-mainvisual');
        var post_html_content = document.querySelector('#news-post-content');
        var post_html_customfield = document.querySelector('#news-post-customfields');
        console.log(post_contents);

        if (post_contents && post_html_date && post_html_category && post_html_title && post_html_content) {
          post_html_date.innerHTML = post_contents.date;
          post_html_category.innerHTML = jsonNews.getPostCategory('news', parseInt(post_contents.category), 'name');
          post_html_title.innerHTML = post_contents.title;
          post_html_content.innerHTML = post_contents.content.the_content;
          post_html_mainvisual.src = post_contents.content.mainvisual;
        }

        if (post_html_customfield && Object.keys(post_contents.content.custom_fields).length > 0) {
          var custom_fields = post_contents.content.custom_fields;
          var articles = custom_fields.articles;
          var rows = custom_fields.rows;
          Object.keys(articles).forEach(function (key) {
            post_html_customfield.innerHTML = post_html_customfield.innerHTML + '<div class="news-details__article">' + '<h4>' + articles[key].title + '</h4>' + '<p>' + articles[key].content + '</p>' + '</div>';
          });
          Object.keys(rows).forEach(function (key, index) {
            var reversal_mod = '';
            var reversal_mod_image = '';
            console.log(index % 2 != 0);

            if (index % 2 != 0) {
              reversal_mod = 'news-details__row--reverse';
              reversal_mod_image = 'news-details__thumbnail2--reverse';
            }

            post_html_customfield.innerHTML = post_html_customfield.innerHTML + '<div class="news-details__row ' + reversal_mod + '">' + '<div class="news-details__thumbnail2 ' + reversal_mod_image + '">' + '<div class="news-details__thumbnail2-ratio"></div>' + '<img src="' + rows[key].banner + '" alt="">' + '</div>' + '<p>' + rows[key].content + '</p>' + '</div>';
          });
        }

        var postList_pagination = jsonNews.getPostList('news', {
          'sort': 'date-desc'
        });
        var post_list_total_pagination = jsonNews.getPostListCount(postList_pagination);
        var prev_page = document.querySelector('.js-prev-page');
        var next_page = document.querySelector('.js-next-page');

        if (prev_page && next_page) {
          Object.keys(postList_pagination).forEach(function (key, index, arr) {
            if (key == post_id) {
              if (index - 1 < 0) {
                prev_page.remove();
              } else {
                prev_page.href = '' + main_base_url + 'news-details.html?post_id=' + arr[index - 1] + '&post_key=' + post_key;
              }

              if (index + 1 >= post_list_total_pagination) {
                console.log(1);
                next_page.remove();
              } else {
                next_page.href = '' + main_base_url + 'news-details.html?post_id=' + arr[index + 1] + '&post_key=' + post_key;
              }
            }
          });
        } //--POST DETAIL PAGE--{END}//

      } //--MAIN{END}//

    };

    xhr.onerror = reject;
    xhr.open('GET', url);
    xhr.send();
  });
}

var jsonNews = function jsonNews() {
  var list = document.querySelector('.js-post-type');

  if (list) {
    loadContents("./jsoncontent.json").catch(function (res) {
      console.log(res);
    });
  }
};

exports.default = jsonNews;
},{"./jsonPostClass":"js/components/jsonPostClass.ts"}],"js/components/jsonVoice.ts":[function(require,module,exports) {
"use strict";
/**
* Json Based news
*/

Object.defineProperty(exports, "__esModule", {
  value: true
});

var jsonPostClass_1 = require("./jsonPostClass");

function loadContents(url) {
  return new Promise(function (resolve, reject) {
    var xhr = new XMLHttpRequest();

    xhr.onload = function () {
      resolve(this.responseText); //--Call base class--{START}//

      var jsonNews = new jsonPostClass_1.jsonPostClass(JSON.parse(this.responseText)); //--Call base class--{END}//
      //--Fixed vars--{START}//

      var main_base_url = '';
      main_base_url = 'https://captainfoods.co.jp/stg/cf2022/'; //Uncomment the code above when building and uploading to FTP or Prod server
      //--Fixed vars--{END}//
      //--Declaration of usable functions--{START}//

      function getPosts(postList, list) {
        var options = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : [];

        if (jsonNews.getPostListCount(postList) >= options.page_limit && jsonNews.getPostListCount(postList) > options.page_start) {
          Object.keys(postList).slice(options.page_start, options.page_limit).forEach(function (key) {
            var postItem = postList[key];
            list.innerHTML = list.innerHTML + '<li class="voice-list__item">' + '<div class="voice-list__image"><img src="' + postItem.content.mainvisual + '" alt=""></div>' + '<div class="voice-list__content">' + '<h2 class="voice-list__title">' + postItem.title + '</h2>' + //'<h4 class="voice-list__name">'+postItem.title+'</h4>'+
            //'<span class="voice-list__date">'+postItem.date+' ／'+jsonNews.getPostCategory('news', parseInt(postItem.category), 'name')+'</span>'+
            '<div class="voice-list__description truncateMe">' + postItem.content.the_content + '</div>' + '<div class="voice-list__read-more">' + '<a class="voice-list__read-more-text" href="' + main_base_url + 'voice-details.html?post_id=' + key + '&post_key=voice">続きを読む</a>' + '</div>' + '</div>' + '</li>';
          });
        } else {
          Object.keys(postList).forEach(function (key) {
            var postItem = postList[key];
            list.innerHTML = list.innerHTML + '<li class="voice-list__item">' + ' <div class="voice-list__image"><img src="' + postItem.content.mainvisual + '" alt=""></div>' + '<div class="voice-list__content">' + '<h2 class="voice-list__title">' + postItem.title + '</h2>' + //'<h4 class="voice-list__name">'+postItem.title+'</h4>'+
            //'<span class="voice-list__date">'+postItem.date+' ／'+jsonNews.getPostCategory('news', parseInt(postItem.category), 'name')+'</span>'+
            '<div class="voice-list__description truncateMe">' + postItem.content.the_content + '</div>' + '<div class="voice-list__read-more">' + '<a class="voice-list__read-more-text" href="' + main_base_url + 'voice-details.html?post_id=' + key + '&post_key=voice">続きを読む</a>' + '</div>' + '</div>' + '</li>';
          });
        }
      } //--Declaration of usable functions--{END}//
      //--MAIN{START}//


      if (jsonNews) {
        //--ALL POST PAGE--{START}//
        var postList = jsonNews.getPostList('voice');
        var list = document.querySelector('.js-load-post');
        var more_button = document.querySelector('.js-more-post');
        var post_list_total = jsonNews.getPostListCount(postList);

        if (list && post_list_total > 0) {
          var page_limit = list.getAttribute('paged_limit');
          page_limit = parseInt(page_limit);

          if (list.getAttribute('paged') == 'paged' && page_limit && page_limit < post_list_total && more_button) {
            var curr_page = parseInt(page_limit);
            var options = {
              'page_start': 0,
              'page_limit': curr_page
            };
            getPosts(postList, list, options);
            more_button.addEventListener('click', function () {
              if (curr_page + page_limit >= post_list_total) {
                options = {
                  'page_start': curr_page,
                  'page_limit': post_list_total
                };
                curr_page = post_list_total;
                more_button.remove();
              } else {
                options = {
                  'page_start': curr_page,
                  'page_limit': curr_page + page_limit
                };
                curr_page = curr_page + page_limit;
              }

              getPosts(postList, list, options);
            });
          } else {
            getPosts(postList, list);
            more_button.remove();
          }
        } //--ALL POST PAGE--{END}//
        //--CATEGORY ITEMS GENERATION--{START}//


        var categ_list = document.querySelector('.js-load-categories');
        var categ_list_json = jsonNews.getCategories('news');

        if (categ_list && categ_list_json) {
          Object.keys(categ_list_json).forEach(function (key) {
            var categ_item = categ_list_json[key];
            categ_list.innerHTML = categ_list.innerHTML + '<a class="news-list__filter-button js-categ-change" categ_id="' + categ_item.id + '" href="' + main_base_url + 'news-list-category.html?categ_id=' + categ_item.id + '">' + categ_item.name + '</a>';
          });
        } //--CATEGORY ITEMS GENERATION--{END}//
        //--CATEGORY PAGE--{START}//


        var base_url = window.location.href;
        var categ_id = base_url.substring(base_url.indexOf('categ_id=') + 'categ_id='.length, base_url.length);
        var categ_urls = document.querySelectorAll('.js-categ-change');

        if (categ_urls) {
          categ_urls.forEach(function (element) {
            element.classList.remove('news-list__filter-button--active');

            if (element.getAttribute('categ_id') == categ_id) {
              element.classList.add('news-list__filter-button--active');
            }
          });
        }

        var list_category = document.querySelector('.js-load-post-categories');
        var postList_category = jsonNews.getPostList('news', {
          'sort': 'date-desc',
          'category_id': categ_id
        });
        var more_button_category = document.querySelector('.js-more-post-category');
        var post_list_total_category = jsonNews.getPostListCount(postList_category);

        if (list_category && post_list_total_category > 0) {
          var page_limit = list_category.getAttribute('paged_limit');
          page_limit = parseInt(page_limit);

          if (list_category.getAttribute('paged') == 'paged' && page_limit && page_limit < post_list_total_category && more_button_category) {
            var curr_page = parseInt(page_limit);
            var options = {
              'page_start': 0,
              'page_limit': curr_page
            };
            getPosts(postList_category, list_category, options);
            more_button_category.addEventListener('click', function () {
              if (curr_page + page_limit >= post_list_total_category) {
                options = {
                  'page_start': curr_page,
                  'page_limit': post_list_total_category
                };
                curr_page = post_list_total_category;
                more_button_category.remove();
              } else {
                options = {
                  'page_start': curr_page,
                  'page_limit': curr_page + page_limit
                };
                curr_page = curr_page + page_limit;
              }

              getPosts(postList_category, list_category, options);
            });
          } else {
            getPosts(postList_category, list_category);
            more_button_category.remove();
          }
        } //--CATEGORY PAGE--{END}//
        //--POST DETAIL PAGE--{START}//


        var base_url_post = window.location.href;
        var base_url_params = base_url_post.substring(base_url.indexOf('?') + 1, base_url_post.length).split('&');
        var post_id = '';
        var post_key = '';
        base_url_params.forEach(function (element) {
          if (element.substring(0, 'post_id='.length) == 'post_id=') {
            post_id = element.substring(element.substring(0, 'post_id='.length).length, element.length);
          } else if (element.substring(0, 'post_key='.length) == 'post_key=') {
            post_key = element.substring(element.substring(0, 'post_key='.length).length, element.length);
          }
        });
        var post_contents = jsonNews.getPostDetails(post_id, post_key);
        var post_html_date = document.querySelector('#news-post-date');
        var post_html_category = document.querySelector('#news-post-category');
        var post_html_title = document.querySelector('#news-post-title');
        var post_html_mainvisual = document.querySelector('#news-post-mainvisual');
        var post_html_content = document.querySelector('#news-post-content');
        var post_html_customfield = document.querySelector('#news-post-customfields');

        if (post_contents && post_html_date && post_html_category && post_html_title && post_html_content) {
          post_html_date.innerHTML = post_contents.date;
          post_html_category.innerHTML = jsonNews.getPostCategory('news', parseInt(post_contents.category), 'name');
          post_html_title.innerHTML = post_contents.title;
          post_html_content.innerHTML = post_contents.content.the_content;
          post_html_mainvisual.src = post_contents.content.mainvisual;
        }

        if (post_html_customfield && Object.keys(post_contents.content.custom_fields).length > 0) {
          var custom_fields = post_contents.content.custom_fields;
          var articles = custom_fields.articles;
          var rows = custom_fields.rows;
          Object.keys(articles).forEach(function (key) {
            post_html_customfield.innerHTML = post_html_customfield.innerHTML + '<div class="news-details__article">' + '<h4>' + articles[key].title + '</h4>' + '<p>' + articles[key].content + '</p>' + '</div>';
          });
          Object.keys(rows).forEach(function (key, index) {
            var reversal_mod = '';
            var reversal_mod_image = '';
            console.log(index % 2 != 0);

            if (index % 2 != 0) {
              reversal_mod = 'news-details__row--reverse';
              reversal_mod_image = 'news-details__thumbnail2--reverse';
            }

            post_html_customfield.innerHTML = post_html_customfield.innerHTML + '<div class="news-details__row ' + reversal_mod + '">' + '<div class="news-details__thumbnail2 ' + reversal_mod_image + '">' + '<div class="news-details__thumbnail2-ratio"></div>' + '<img src="' + rows[key].banner + '" alt="">' + '</div>' + '<p>' + rows[key].content + '</p>' + '</div>';
          });
        }

        var postList_pagination = jsonNews.getPostList('news', {
          'sort': 'date-desc'
        });
        var post_list_total_pagination = jsonNews.getPostListCount(postList_pagination);
        var prev_page = document.querySelector('.js-prev-page');
        var next_page = document.querySelector('.js-next-page');

        if (prev_page && next_page) {
          Object.keys(postList_pagination).forEach(function (key, index, arr) {
            if (key == post_id) {
              if (index - 1 < 0) {
                prev_page.remove();
              } else {
                prev_page.href = '' + main_base_url + 'news-details.html?post_id=' + arr[index - 1] + '&post_key=' + post_key;
              }

              if (index + 1 >= post_list_total_pagination) {
                console.log(1);
                next_page.remove();
              } else {
                next_page.href = '' + main_base_url + 'news-details.html?post_id=' + arr[index + 1] + '&post_key=' + post_key;
              }
            }
          });
        } //--POST DETAIL PAGE--{END}//

      } //--MAIN{END}//

    };

    xhr.onerror = reject;
    xhr.open('GET', url);
    xhr.send();
  });
}

var jsonNews = function jsonNews() {
  var list = document.querySelector('.js-post-voice');

  if (list) {
    loadContents("./jsoncontent.json").catch(function (res) {
      console.log(res);
    });
  }
};

exports.default = jsonNews;
},{"./jsonPostClass":"js/components/jsonPostClass.ts"}],"js/index.ts":[function(require,module,exports) {
"use strict";

var __importDefault = this && this.__importDefault || function (mod) {
  return mod && mod.__esModule ? mod : {
    "default": mod
  };
};

Object.defineProperty(exports, "__esModule", {
  value: true
});

require("regenerator-runtime/runtime");

var forEachPolyfill_1 = __importDefault(require("./components/forEachPolyfill"));

var intializeBanner_1 = __importDefault(require("./components/intializeBanner"));

var toggleMenu_1 = __importDefault(require("./components/toggleMenu"));

var faqAccordion_1 = __importDefault(require("./components/faqAccordion"));

var getZip_1 = __importDefault(require("./components/getZip"));

var showMore_1 = __importDefault(require("./components/showMore"));

var clampText_1 = __importDefault(require("./components/clampText"));

var sideSublistToggle_1 = __importDefault(require("./components/sideSublistToggle"));

var tabToggle_1 = __importDefault(require("./components/tabToggle"));

var jsonNews_1 = __importDefault(require("./components/jsonNews"));

var jsonVoice_1 = __importDefault(require("./components/jsonVoice"));

document.addEventListener('DOMContentLoaded', function () {
  (0, forEachPolyfill_1.default)();
  (0, intializeBanner_1.default)();
  (0, toggleMenu_1.default)();
  (0, faqAccordion_1.default)();
  (0, getZip_1.default)();
  (0, showMore_1.default)();
  (0, clampText_1.default)();
  (0, sideSublistToggle_1.default)();
  (0, tabToggle_1.default)();
  (0, jsonNews_1.default)();
  (0, jsonVoice_1.default)();
}, false);
},{"regenerator-runtime/runtime":"../node_modules/regenerator-runtime/runtime.js","./components/forEachPolyfill":"js/components/forEachPolyfill.ts","./components/intializeBanner":"js/components/intializeBanner.ts","./components/toggleMenu":"js/components/toggleMenu.ts","./components/faqAccordion":"js/components/faqAccordion.ts","./components/getZip":"js/components/getZip.ts","./components/showMore":"js/components/showMore.ts","./components/clampText":"js/components/clampText.ts","./components/sideSublistToggle":"js/components/sideSublistToggle.ts","./components/tabToggle":"js/components/tabToggle.ts","./components/jsonNews":"js/components/jsonNews.ts","./components/jsonVoice":"js/components/jsonVoice.ts"}]},{},["js/index.ts"], null)
//# sourceMappingURL=/js/index.js.map