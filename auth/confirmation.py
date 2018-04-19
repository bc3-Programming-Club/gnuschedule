from time import time, sleep
from uuid import uuid4

# class User:
#     objects = []

#     @staticmethod
#     def confirmed(email):
#         return Confirmation.of('user.email:{}'.format(email))

#     def __init__(self, email=None):
#         self.email = email
#         User.objects.append(self)

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

class Confirmation:
    objects = []

    @staticmethod
    def of(target):
        conf = Confirmation.find_by_target(target)
        if conf:
            return conf.resolved
        else:
            return False

    @staticmethod
    def find_or_create(target):
        conf = Confirmation.find_by_target(target)
        return conf or Confirmation(target=target)
        

    @staticmethod
    def find_by_target(target):
        for conf in Confirmation.objects:
            if conf.target == target:
                return conf

    @staticmethod
    def find_and_resolve(code):
        for conf in Confirmation.objects:
            if conf._token.value == code:
                return conf.resolve(code)

    def __init__(self, target=None):
        self._resolved = False
        self.target = target
        Confirmation.objects.append(self)

    def renew(self, ttl=3600):
        self._resolved = False
        self._token = Token(value=uuid4(), ttl=ttl)
        return self._token.value

    @property
    def resolved(self):
        return self._resolved

    def resolve(self, code):
        if not self._token.expired and code == self._token.value:
            self._resolved = True
            return True
        else:
            return False

def mail(*a, **kw):
    print("mail({}, {})".format(a, kw))

sender = "no-reply@gnuschedule.com"

def on_register(email):
    on_send_confirmation(email)

def on_send_confirmation(email):
    conf = Confirmation.find_or_create('user.email:{}'.format(email))
    code = conf.renew(3)

    mail(
        email, 
        "Confirm Your Account", """
    Hello {email},

    Please confirm your email address by clicking this button.

    <a class="btn" href="http://gnuschedule.com/confirm/{code}">CONFIRM</a>
    """.format(code=code, email=email), 
    sender)

    return code

def on_confirmation_clicked(code):
    if Confirmation.find_and_resolve(code):
        return "Your email has been confirmed"
    else:
        return "Sorry, the confirmation failed."

def __test_module__():

    # Confirmation TTL = 3 seconds
    
    code = on_send_confirmation('darius.montez@gmail.com')
    sleep(4)  # wait for confirmation to expire
    res = on_confirmation_clicked(code)
    assert 'failed' in res
    assert not Confirmation.of('user.email:darius.montez@gmail.com')
    # assert not User.confirmed('darius.montez@gmail.com')

    code = on_send_confirmation('darius.montez@gmail.com')
    sleep(1)  # confirm in good time
    res = on_confirmation_clicked(code)
    assert 'confirmed' in res
    assert Confirmation.of('user.email:darius.montez@gmail.com')
    # assert User.confirmed('darius.montez@gmail.com')

    print("\033[32m=== module tests pass ({}) ===\033[0m".format(__name__))


if __name__ == '__main__':
    __test_module__()