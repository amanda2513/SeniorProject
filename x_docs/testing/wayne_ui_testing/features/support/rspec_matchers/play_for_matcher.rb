RSpec::Matchers.define :play_for do |time_in_seconds|
  
  match do |media|
    media.played_for time_in_seconds
  end

  failure_message_for_should do |media|
    "expected media to have played for #{time_in_seconds} seconds\nLOG:\n#{media}"
  end
  
end