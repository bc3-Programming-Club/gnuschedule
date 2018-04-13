#!/bin/bash/python3

from threading import Thread
from time import time, sleep
from uuid import uuid4

class Token:
    def __init__(self, value, ttl):
        self.value = value
        self.ttl = ttl
        self.created_at = time()

    @property
    def age(self):
        return time() - self.created_at

    @property
    def expired(self):
        return self.age > self.ttl


class TokenSet:
    def __init__(self, generator=uuid4, ttl=3600):
        self.default_generator = generator
        self.default_ttl = ttl
        self.tokens = []

        self.thread = Thread(target=self.run)
        self.thread.daemon = True
        self.thread.start()

    def _delete_expired_tokens(self):
        for token in self.tokens:
            if token.expired:
                self.tokens.remove(token)

    def run(self):
        while True:
            self._delete_expired_tokens()
            sleep(0.5)

    def create_token(self):
        token = Token(self.default_generator(), self.default_ttl)
        self.tokens.append(token)
        return token

    def retrieve_token(self, value):
        for token in self.tokens:
            if token.value == value:
                return token

    def __iter__(self):
        return self.tokens.__iter__()



if __name__ == '__main__':
    tokens = TokenSet(ttl=1)
    t = tokens.create_token()
    print("Token created: {}".format(t.value))


    assert t in tokens
    sleep(3)
    assert t.expired
    assert t not in tokens

    # loop = asyncio.get_event_loop()
    # # Blocking call which returns when the hello_world() coroutine is done
    # loop.run_until_complete(hello_world())
    # loop.close()